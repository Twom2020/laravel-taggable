## Laravel File Manager

### Installation:
```
composer require twom/laravel-taggable
```

You must add the service provider to `config/app.php`
``` php
'providers' => [
	 // for laravel 5.8 and below
	 \Twom\Taggable\TwomTaggableServiceProvider::class,
];
```

**Publish your config file and migrations**

```
php artisan vendor:publish
```

**Run migration**
> **Note:** create taggable tables.
```
php artisan migrate
```
<hr>

### Config:
> config/taggable.php
``` php
return [  
    'model' => \Twom\Taggable\Models\Tag::class,
    'filter_condition' => 'where', // can be 'like', this is default condition
];
```


## Lets start to use:

#### Your taggable model:
> **Note:** should be use the **Taggable** trait from `Twom\Taggable\Traits\Taggable`
```php  
namespace App;  
  
use Illuminate\Database\Eloquent\Model;  
use Twom\Taggable\Traits\Taggable;  
  
class Post extends Model  
{  
  use Taggable;  
  
  public $timestamps = false;  
  
  protected $fillable = [  
	  'title', // and another fields
  ];  
}
```

#### use of taggable options:
```php
/** @var Post $post */
$post = Post::query()->find(1);

//	just add (attach) tags
$post->tag("sport,gym");

//	sync tags, detach all and attach passed tags
$post->stag("football");

//	delete (detach) tags
$post->detag("football");
```
| type     | example  |
|----------|----------|
| string   | `"first tag,second tag"` |
| array    | `["first tag", "second tag"]`  |
