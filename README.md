# LiveWire

## Intro

1. install from composer livewire

2. make class

    ```bash
    php artisan make:livewire counter --inline # not seperated class & blade
    ```

3 . add to blade file

```css
<livewire: counter> @livewireScripts;
```

faking live experience

-   pretend there is live wire browser - server . we can interact with server
-   under the hood :
    -   request to server and comming back

## 2. from scratch

-   blade facade::render
-   getProperties - > array of key & value pairs - PHP Reflection API (inspect php code itself)
-   put into class

​ inside blade file -> turn code into blade directive

```php
        @livewire(App\Http\Livewire\Counter::class)
```

```php
Blade::directive('livewire', function ($expression) {
    return "<?php echo (new App\Livewire)->initialRender({$expression});?>";
});

```
