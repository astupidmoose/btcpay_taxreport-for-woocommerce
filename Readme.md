# Tax Reports / Transaction History for Woocommerce Clients

This plugin allows your clients to download transaction history. It's main purpose is to allow the client to be able to get the following fields for reporting purposes:

Order Number
Order Status
Order Date
Email (Billing)
Order Total
Order Total Tax Amount (Sales Tax)
BTC Address
BTC Amount
BTC Rate
This plugin only shows orders which have had the "Date_Paid" fieldset, to make sure cancelled orders and orders which are not paid are not included.

This plugin only shows the BTC amounts/equivalents. This will not show Altcoin payments but will show their BTC equivalents.

### Editing Customer Area:

If you wish to edit what the customer sees, or perhaps add your own HTML intro to the reporting page, you can add HTML above the following lines:

    <form action="" method="post">
    <select class="taxreport-select" name="taxreport_year" id="taxreport_year">';



### Editing Fields:

If you would like add or delete fields to the report, find the `$columns =[` line in class-taxreport.php. Here you can copy/paste any new values you wish to add. 


## If you found this plugin useful, please consider helping pay for its development:

This plugin was fully funded by www.Coincards.com and open sourced. 

If you like this plugin or find it useful, we would appreciate your support in pitching in for it's development: https://btcpay.stufftech.io/apps/3dXsE82sCftW24avWm4mcLvNiZ3g/crowdfund
