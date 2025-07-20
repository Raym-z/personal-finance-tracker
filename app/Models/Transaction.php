<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Transaction extends Model
{
    protected $fillable = [
        'description',
        'amount',
        'type',
        'user_id',
        'tag',
        'image_path',
    ];

    public function user()
    {
        return $this->belongsTo(User::class);
    }

    /**
     * Get the color for a tag
     */
    public function getTagColorAttribute()
    {
        return self::getTagColor($this->tag, $this->user_id);
    }

    /**
     * Get the color for a specific tag
     */
    public static function getTagColor($tag, $userId = null)
    {
        // If user ID is provided, check for custom tags first
        if ($userId) {
            $customTags = \App\Models\UserSetting::getSetting($userId, 'custom_tags', []);
            if (isset($customTags[$tag])) {
                // Convert hex color to Bootstrap color class
                $hexColor = $customTags[$tag]['color'];
                return self::hexToBootstrapColor($hexColor);
            }
        }

        // Fallback to default colors for backward compatibility
        $defaultColors = [
            // Income tags
            'Salary' => 'success',
            'Freelance' => 'info',
            'Investment' => 'primary',
            'Gift' => 'warning',
            'Bonus' => 'success',
            'Other' => 'secondary',
            
            // Expense tags
            'Food' => 'danger',
            'Transportation' => 'primary',
            'Housing' => 'dark',
            'Utilities' => 'info',
            'Entertainment' => 'warning',
            'Healthcare' => 'danger',
            'Shopping' => 'primary',
            'Education' => 'info',
        ];

        return $defaultColors[$tag] ?? 'secondary';
    }

    /**
     * Get the hex color for a tag (for settings page)
     */
    public static function getTagHexColor($tag, $userId = null)
    {
        $defaultColors = [
            // Income tags
            'Salary' => '#198754',
            'Freelance' => '#0dcaf0',
            'Investment' => '#0d6efd',
            'Gift' => '#ffc107',
            'Bonus' => '#198754',
            'Other' => '#6c757d',
            
            // Expense tags
            'Food' => '#dc3545',
            'Transportation' => '#0d6efd',
            'Housing' => '#212529',
            'Utilities' => '#0dcaf0',
            'Entertainment' => '#ffc107',
            'Healthcare' => '#dc3545',
            'Shopping' => '#0d6efd',
            'Education' => '#0dcaf0',
        ];

        // If user ID is provided, check for custom colors and custom tags
        if ($userId) {
            // Check for custom tag colors first
            $customColors = \App\Models\UserSetting::getSetting($userId, 'tag_colors', []);
            if (isset($customColors[$tag])) {
                return $customColors[$tag];
            }
            
            // Check for custom tags
            $customTags = \App\Models\UserSetting::getSetting($userId, 'custom_tags', []);
            if (isset($customTags[$tag])) {
                return $customTags[$tag]['color'];
            }
        }

        return $defaultColors[$tag] ?? '#6c757d';
    }

    /**
     * Get the text color for a background color (for contrast)
     */
    public static function getTextColor($backgroundColor)
    {
        // Remove # if present
        $hex = ltrim($backgroundColor, '#');
        
        // Convert to RGB
        $r = hexdec(substr($hex, 0, 2));
        $g = hexdec(substr($hex, 2, 2));
        $b = hexdec(substr($hex, 4, 2));
        
        // Calculate luminance
        $luminance = (0.299 * $r + 0.587 * $g + 0.114 * $b) / 255;
        
        // Return white for dark backgrounds, black for light backgrounds
        return $luminance > 0.5 ? '#000000' : '#ffffff';
    }

    /**
     * Get all tag colors mapping (for backward compatibility)
     */
    public static function getTagColors()
    {
        return [
            // Income tags
            'Salary' => '#198754',
            'Freelance' => '#0dcaf0',
            'Investment' => '#0d6efd',
            'Gift' => '#ffc107',
            'Bonus' => '#198754',
            'Other' => '#6c757d',
            
            // Expense tags
            'Food' => '#dc3545',
            'Transportation' => '#0d6efd',
            'Housing' => '#212529',
            'Utilities' => '#0dcaf0',
            'Entertainment' => '#ffc107',
            'Healthcare' => '#dc3545',
            'Shopping' => '#0d6efd',
            'Education' => '#0dcaf0',
        ];
    }

    /**
     * Convert hex color to Bootstrap color class
     */
    private static function hexToBootstrapColor($hexColor)
    {
        $colorMap = [
            '#198754' => 'success',   // Green
            '#0dcaf0' => 'info',      // Cyan
            '#0d6efd' => 'primary',   // Blue
            '#ffc107' => 'warning',   // Yellow
            '#dc3545' => 'danger',    // Red
            '#6c757d' => 'secondary', // Gray
            '#212529' => 'dark',      // Dark
            '#f8f9fa' => 'light',     // Light
        ];

        return $colorMap[$hexColor] ?? 'secondary';
    }

    /**
     * Get all available tags for a user (including custom tags)
     */
    public static function getAllTags($userId = null)
    {
        if ($userId) {
            // Get all tags from user settings (now all tags are stored as custom tags)
            $customTags = \App\Models\UserSetting::getSetting($userId, 'custom_tags', []);
            
            $incomeTags = [];
            $expenseTags = [];
            
            foreach ($customTags as $tagName => $tagInfo) {
                if ($tagInfo['type'] === 'income') {
                    $incomeTags[] = $tagName;
                } else {
                    $expenseTags[] = $tagName;
                }
            }
            
            return [
                'income' => $incomeTags,
                'expense' => $expenseTags
            ];
        }

        // Fallback to predefined tags for backward compatibility
        return [
            'income' => ['Salary', 'Freelance', 'Investment', 'Gift', 'Bonus', 'Other'],
            'expense' => ['Food', 'Transportation', 'Housing', 'Utilities', 'Entertainment', 'Healthcare', 'Shopping', 'Education', 'Other']
        ];
    }
}