
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
 - this is race condition when multiple constumer checkout same product at the same time.
 - there's no inventory checking after succeed payment leading to wrong inventory quantity

2.
proposed solution:
- create lock mechanism to prevent same product id checkout at the same time, give a timeout for next process
- create API to invalidate other active order once stock is empty
- db pessimistic locking to prevent update table at the same time


3. run the app
- run `composer install`
- edit .env file based on db configuration
- run `php dbseed.php`
- run `php -S localhost:8080 -t public`


