<?php

namespace App\Helpers;

class IconHelper
{
    /**
     * Get an SVG icon component
     */
    public static function icon($name, $attributes = [])
    {
        $width = $attributes['width'] ?? null;
        $height = $attributes['height'] ?? null;
        $class = $attributes['class'] ?? '';
        
        // Remove these from attributes since they're handled separately
        unset($attributes['width'], $attributes['height'], $attributes['class']);
        
        $attributeString = '';
        foreach ($attributes as $key => $value) {
            $attributeString .= " {$key}=\"{$value}\"";
        }
        
        return "<x-icons.{$name} width=\"{$width}\" height=\"{$height}\" class=\"{$class}\"{$attributeString} />";
    }
    
    /**
     * Get filter icon
     */
    public static function filter($attributes = [])
    {
        return self::icon('filter', $attributes);
    }
    
    /**
     * Get image icon
     */
    public static function image($attributes = [])
    {
        return self::icon('image', $attributes);
    }
    
    /**
     * Get three dots menu icon
     */
    public static function threeDots($attributes = [])
    {
        return self::icon('three-dots', $attributes);
    }
    
    /**
     * Get edit icon
     */
    public static function edit($attributes = [])
    {
        return self::icon('edit', $attributes);
    }
    
    /**
     * Get delete icon
     */
    public static function delete($attributes = [])
    {
        return self::icon('delete', $attributes);
    }
    
    /**
     * Get plus circle icon
     */
    public static function plusCircle($attributes = [])
    {
        return self::icon('plus-circle', $attributes);
    }
} 