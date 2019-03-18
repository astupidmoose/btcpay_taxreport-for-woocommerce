# Tax Reports / Transaction History for Woocommerce Clients

This plugin allows your clients to download transaction history. It's main purpose is to allow the client to be able to get the following fields for reporting purposes:

- Order Number
- Order Status
- Order Date
- Email (Billing)
- Order Total
- Order Total Tax Amount (Sales Tax)
- BTC Address
- BTC Amount
- BTC Rate

This plugin only shows orders which have had the "Date_Paid" field set, to make sure cancelled orders and orders which are not paid are not included. 

This plugin only shows the BTC amounts/equivalents. This will not show Altcoin payments, but will show their BTC equivelants. 

### Editing Customer Area:

If you wish to edit what the customer sees, or perhaps add your own HTML intro to the reporting page, you can add HTML above the following lines:

    <form action="" method="post">
    <select class="taxreport-select" name="taxreport_year" id="taxreport_year">';



### Editing Fields:

If you would like add or delete fields to the report, find the `$columns =[` line in class-taxreport.php. Here you can copy/paste any new values you wish to add. 

