# Run this script as Administrator to allow SMTP ports through Windows Firewall

Write-Host "Creating Windows Firewall rule for SMTP..." -ForegroundColor Cyan

# Create outbound rule for SMTP ports
New-NetFirewallRule -DisplayName "SMTP Outbound (Port 587)" -Direction Outbound -Protocol TCP -LocalPort 587 -Action Allow -Profile Any
New-NetFirewallRule -DisplayName "SMTP Outbound (Port 465)" -Direction Outbound -Protocol TCP -LocalPort 465 -Action Allow -Profile Any
New-NetFirewallRule -DisplayName "SMTP Outbound (Port 25)" -Direction Outbound -Protocol TCP -LocalPort 25 -Action Allow -Profile Any

Write-Host "Firewall rules created successfully!" -ForegroundColor Green
Write-Host "SMTP ports 25, 465, and 587 are now allowed." -ForegroundColor Green

# Display the created rules
Write-Host "`nVerifying rules..." -ForegroundColor Cyan
Get-NetFirewallRule -DisplayName "SMTP*" | Select-Object DisplayName, Enabled, Direction, Action

Write-Host "`nYou can now use Gmail SMTP for sending emails!" -ForegroundColor Green
