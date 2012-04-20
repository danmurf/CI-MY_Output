CodeIgniter Core Output extension
=============

This file extends the functionality of CodeIgniter's core output library. This has been tested with CodeIgniter 2.1.0.

##Installation

Copy the `MY_Output.php` file to your `application/core` folder. Assuming your config is still set to use the default 'MY_' prefix for library extensions, the library will be automatically loaded. If not, you will need to amend the MY_Output.php file name and class name appropriately.

##Clearing a cached path

To clear a cache file for a specific URI path use:

    $this->output->clear_path_cache($uri);

This will return `TRUE` if successfull, `FALSE` if not.

##Clearing all cache

To clear the cache for all URI paths use:

    $this->output->clear_all_cache();

##Checking to see if a path is cached

You can check to see if a specific path is cached already by using:

    $this->output->path_cached($uri);

This will return `TRUE` if the path is already cached, `FALSE` if not.

##Checking the expiration time of a cached path

You can check to see when a cache file is set to expire by using:

    $this->output->get_path_cache_expiration($uri);

This will return the unix timestamp (as an `int`) of the expiration or `FALSE` if the cache file does not exist.