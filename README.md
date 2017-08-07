# GraphQL Files Bundle

Symfony bundle for easy implementation images and files to your GraphQL API (bundle with GraphQL implementation and its documentation is [here](https://github.com/Youshido/GraphQLBundle)). Bundle provides `UploadImageMutation`:
``` graphql
mutation {
  uploadImage(field: "image") {
    id
    url
    fileName
    mimeType
    extension
    size
    resized(width: 100, height: 100, mode: INSET) {
      url
    }
  }
}

```

Mutation assumes that request content-type is `multipart/form-data` and include image data in field that is passed as argument `field`.
Upload file mutation:
``` graphql
mutation {
  uploadFile(field: "image") {
    id
    url
    fileName
    mimeType
    extension
    size
  }
}

```

Also bundle provides `ImageField` to use in your API like this:
``` graphql
{
  me {
    id
    firstName
    lastName
    image { // image field from bundle
      url
      resized(width: 100, height: 100, mode: INSET) {
        url
      }
    }
  }
}
```
or you can add arguments directly to the image field for your convenience.
``` graphql
{
  me {
    id
    firstName
    lastName
    small: image(width: 100, height: 100, mode: INSET) { // resized directly
      url
    }    
    medium: image(width: 500, height: 300, mode: OUTBOUND) { // different mode
      url
    }    
    fullSize: image {
      url
    }
  }
}
```

## How to use
* [Installation](#1-installation)
* [Configuration](#2-configuration)
* [GraphQL schema set-up](#3-set-up-graphql-schema)
  * [Mutation type](#31-add-uploadimagemutation-to-your-mutationtype)
  * [Custom type](#32-add-image-field-to-your-type)

### 1. Installation:
> composer require youshido/graphql-files-bundle

### 2. Configuration:
#### 2.1 Enable bundle in your `AppKernel.php`:

``` php
$bundles[] = new Youshido\GraphQLFilesBundle\GraphQLFilesBundle()
```

#### 2.2. Add new routing in `routing.yml`:
``` yaml
graphql_file.image_resizer:
    resource: "@GraphQLFilesBundle/Resources/config/routing.yml"

```

#### 2.3. Configurate bundle in `config.yml`
This is full configuration and by default are not needed:
``` yaml
graph_ql_files:
    image_driver: gd     #imagine driver, can be gd, imagick or gmagick
    storage: local       #or s3
    platform: orm        #or odm
    local:                                     #config for local storage
        web_root: "%kernel.root_dir%/../web"
        path_prefix: "uploads"
    s3:                                        #config for s3 storage 
        client: ~                              #s3 client service
        bucket: ~                               
        directory: ''
    models:
        image_validation_model: Youshido\GraphQLFilesBundle\Model\Validation\ImageValidationModel
        file_validation_model: Youshido\GraphQLFilesBundle\Model\Validation\FileValidationModel
        orm:
            image: Youshido\GraphQLFilesBundle\Entity\Image
            file: Youshido\GraphQLFilesBundle\Entity\File
        odm:
            image: Youshido\GraphQLFilesBundle\Document\Image
            file: Youshido\GraphQLFilesBundle\Document\File

```

### 3. Set-up GraphQL schema:
#### 3.1 Add `UploadImageMutation` to your `MutationType`:
```php
<?php

use Youshido\GraphQLFilesBundle\GraphQL\Field\UploadBase64ImageField;
use Youshido\GraphQLFilesBundle\GraphQL\Field\UploadImageField;
use Youshido\GraphQLFilesBundle\GraphQL\Field\UploadFileField;

class MutationType extends AbstractObjectType
{

    public function build($config)
    {

        $config->addFields([
            // images
            new UploadBase64ImageField(),
            new UploadImageField(),
            
            // files
            new UploadFileField(),

            // other mutations
        ]);

    }
}

```

#### 3.2 Add image field to your type:
``` php

use Youshido\GraphQLFilesBundle\GraphQL\Field\ImageField;

class YourType extends AbstractObjectType
{

    public function build($config)
    {
        $config->addFields([
            // your type fields
        
            new ImageField()
        ]);
    }
}
```
