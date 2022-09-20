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

## 3. Core

ajax request is sent to server - do sth - render back - js will swap

we need a "snapshot" of component (our Counter class) and embed into Javascript

1. have div element with wire - data set
2. with JS go through each element and pull out data
3. turn that string into an actual JS object - jsonencode / then json parse

```js
    function initialRender($class)
    {
        $component = new $class;
        $html =  Blade::render(
            $component->render(),
            $this->getProperties($component)
        );
        $snapshot = [
            'class' => get_class($component),
            'data' => $this->getProperties($component),
        ];
        $snapshotAttr = htmlentities(json_encode($snapshot));
        return <<<HTML
                <div wire:snapshot="{$snapshotAttr}">
                    {$html}
                </div>
        HTML;
    }
```

browser have problem -> wrap with htmlentities to escape quotes

JS -> grab

```js
document.querySelectorAll("[wire\\:snapshot]").forEach((el) => {
    let snapshot = JSON.parse(el.getAttribute("wire:snapshot"));
    console.log(snapshot);
});
```

## 4. Trigger Server
