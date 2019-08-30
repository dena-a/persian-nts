# Persian Number to Speech
a Laravel Number to Speech package for Persian language

## Usage

```PHP
use Dena\PersianNTS\PersianNTS;

$audio = (new PersianNTS)
    ->setNumber(1000)
    ->toToman()
    ->mergeInfront(storage_path('front.mp3'))
    ->mergeBehind(storage_path('behind.mp3'))
    ->getSpeech()
    ->setSavePath(storage_path('temp.mp3'))
    ->save()
    ->getSavePath();

return response()->file($audio, [
    "Content-Type" => "audio/mpeg"
]);
```