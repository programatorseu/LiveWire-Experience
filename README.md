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

-   looking for wire-click
-   use fetch & json.stringify
-   csrf token - verify csrf token
-   send method (increment) to server

```js
document.querySelectorAll("[wire\\:snapshot]").forEach((el) => {
    let snapshot = JSON.parse(el.getAttribute("wire:snapshot"));
    el.addEventListener("click", (e) => {
        if (!e.target.hasAttribute("wire:click")) return;
        let method = e.target.getAttribute("wire:click");

        fetch("/livewire", {
            method: "POST",
            headers: { "Content-Type": "application/json" },
            body: JSON.stringify({
                snapshot: snapshot,
                callMethod: method,
            }),
        });
    });
});
```

### 5. Handling Request

1. get component from snaphost
2. call method on component
3. turn component back into a snaphost and get HTML
    - destruct with tuple
4. send data to front-end

```php
    $component = (new Livewire)->fromSnapshot(request('snapshot'));
    if ($method = request('callMethod')) {
        (new Livewire)->callMethod($component, $method);
    }
    [$html, $snapshot] = (new Livewire)->toSnapshot($component);
    return [
        'html' => $html,
        'snapshot' => $snapshot
    ];
```
