
1.
what might happened :
 - two or more customers put the same product to cart. 
 - at this point the product is considered in stock, since none of them has paid for it yet.
 - both customers almost simultaneously proceed to checkout. 
 - Since no money withdrawn from either of them yet, the product is considered in stock.
 - both customers pay.
 - the first customer returns to store with the paid order. 
 - system reduces the number of items in stock to zero and completes the order for this customer.
 - the second customer returns to store with the paid order. 
 - system reduces the number of items in stock negative (-1).

why this happened:
this is race condition when multiple constumer checkout same product at the same time.

2.
proposed solution:
- create lock mechanism to prevent same product id checkout at the same time, give a timeout for next process
- create API to invalidate other active order once stock is empty
- mysql pesimistic lock to prevent update table at the same time
- create messaging system in payment so that the payment system run in quenue


3. run the app
- run composer install
- edit .env file based on db configuration
- php dbseed.php
- run php -S localhost:8080 -t public
