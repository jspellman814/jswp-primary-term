# Primary Term

## Description
WordPress plugin that allows authors to select a primary term if multiple categories are chosen.

### Usage
The primary term ID is saved in the wp_postmeta table for each post. 

To filter posts by primary term use:

```
'meta_key'   => 'jswp_pt',  
'meta_value' => term_id,
```  

### Supports
* WordPress' built in categories
* Custom taxonomies
* Custom post types