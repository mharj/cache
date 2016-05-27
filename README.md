# PHP cache

generic key/value cache system via callback
```php
$cache = new xxxCache();
$value = $cache->get($key, function() {
    return "value";
});
```
Passing data/objects without globals (data/objects won't change cache status, only key does or delete)
```
$cache = new xxxCache();
$value = $cache->get($key, $param1, $param2, ... , function($param1,$param2,...) {
    return $param1.$param2.$param3...
});
```
Or cache stack
```
$cache = new StackCache(array(
    new xxxCache(),
    new yyyCache(),
    new zzzCache()
));
$value = $cache->get($key, function() {
    return "value";
});
```
