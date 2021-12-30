<# 
.SYNOPSIS
    The job for 'account lockouts' runs on DC02, it is a triggered task for event id 4740.  
    The script writes the newest event to a table on a MySQL DB on Server1 (credentials can be found in LP).
    A PHP file is triggered when the webpage is opened, located on Server02. 
    The webpage will trigger a function calling a sql db connection to Server1 and pull the info for the day.

.INPUTS
    Pull password for sysman 
.OUTPUTS
    

.NOTES
    


.EXAMPLE

#>

Import-Module ActiveDirectory
Import-Module SimplySQL

#grabbing encrypted pw
[Byte[]] $key = (1..32)
$pass = get-content 'Password.txt' | ConvertTo-SecureString -Key $key
$SQLCred = new-object -typename System.Management.Automation.PSCredential -argumentlist "SQLAdmin", $pass

Open-MySqlConnection -Server 1.1.1.1 -Database "DB" -Credential $SQLCred -ConnectionName "data"

$properties = @(
    'TimeCreated',
    @{n='Account Name';e={$_.Properties[0].Value}},
    @{n='Caller Computer Name';e={$_.Properties[1].Value}}
)
$event = Get-WinEvent -MaxEvents 1 -FilterHashTable @{LogName='Security'; ID=4740} | Select $properties

$user = $event.'Account Name'
$caller = $event.'Caller Computer Name'
$time = $event.TimeCreated

$account = Get-AdUser $user -Properties * | select TimeGenerated,DisplayName,l,Title,CanonicalName,LockedOut,PasswordLastSet

$displayName = $account.DisplayName
$location = $account.l
$pwLstSet = $account.PasswordLastSet
$title = $account.Title
$ADLoc = $account.CanonicalName
$ADLoc = $ADLoc.Replace("domain.com","")
$LockedOut = $account.LockedOut

$time,$displayName,$title,$location,$pwLstSet,$ADLoc,$LockedOut

Invoke-SQLQuery "INSERT INTO AccountLockouts (Caller,User,Office,Time,Title,ADLocation,LockedOut,PwdLastSet) VALUES ('$caller','$displayName','$location','$time','$title','$ADLoc','$LockedOut','$pwLstSet')" -ConnectionName "data"


Close-SqlConnection -ConnectionName "data"

# Error notification
trap {
$body = $_|out-string
Send-MailMessage -To "Network Monitor <address@domain.com>" -From "SQLAdmin <address@domain.com>" -subject "Script AccountLockout Execution Error" -Body "$body" -priority High -BodyAsHtml -smtpserver exchangeserver1
break
}