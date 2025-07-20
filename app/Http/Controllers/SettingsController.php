<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use App\Models\UserSetting;
use App\Models\Transaction;

class SettingsController extends Controller
{
    public function index(Request $request)
    {
        $user = Auth::user();
        $sortBy = $request->query('sort', 'alphabetical'); // Default to alphabetical sorting
        
        // Get all tags (now all stored as custom tags)
        $customTags = UserSetting::getSetting($user->id, 'custom_tags', []);
        
        // If no tags exist, create default tags for this user
        if (empty($customTags)) {
            \Database\Seeders\DefaultTagsSeeder::createDefaultTagsForUser($user->id);
            $customTags = UserSetting::getSetting($user->id, 'custom_tags', []);
        }
        
        // Organize tags by type and add creation time for sorting
        $incomeTags = [];
        $expenseTags = [];
        
        foreach ($customTags as $tag => $tagInfo) {
            $tagData = [
                'name' => $tag,
                'color' => $tagInfo['color'],
                'is_custom' => true,
                'type' => $tagInfo['type'],
                'created_at' => $tagInfo['created_at'] ?? now()->toISOString() // Default creation time for existing tags
            ];
            
            if ($tagInfo['type'] === 'income') {
                $incomeTags[$tag] = $tagData;
            } else {
                $expenseTags[$tag] = $tagData;
            }
        }
        
        // Sort tags based on user preference
        if ($sortBy === 'creation_time') {
            // Sort by creation time (newest first)
            uasort($incomeTags, function($a, $b) {
                return strtotime($b['created_at']) - strtotime($a['created_at']);
            });
            
            uasort($expenseTags, function($a, $b) {
                return strtotime($b['created_at']) - strtotime($a['created_at']);
            });
        } else {
            // Default: Sort alphabetically by name
            uasort($incomeTags, function($a, $b) {
                return strcasecmp($a['name'], $b['name']);
            });
            
            uasort($expenseTags, function($a, $b) {
                return strcasecmp($a['name'], $b['name']);
            });
        }
        
        return view('settings', [
            'tagColors' => array_column($customTags, 'color', 'name'),
            'customTags' => $customTags,
            'incomeTags' => $incomeTags,
            'expenseTags' => $expenseTags,
            'sortBy' => $sortBy,
        ]);
    }



    public function createCustomTag(Request $request)
    {
        $request->validate([
            'tag_name' => 'required|string|max:50|regex:/^[a-zA-Z0-9\s\-_]+$/',
            'tag_type' => 'required|in:income,expense',
            'tag_color' => 'required|string|regex:/^#[0-9A-F]{6}$/i',
        ]);

        $user = Auth::user();
        $tagName = trim($request->input('tag_name'));
        
        // Get existing custom tags
        $customTags = UserSetting::getSetting($user->id, 'custom_tags', []);
        
        // Check if tag already exists
        if (isset($customTags[$tagName])) {
            return redirect()->route('settings.index')->withErrors(['tag_name' => 'This tag already exists.']);
        }
        
        // Add new custom tag
        $customTags[$tagName] = [
            'type' => $request->input('tag_type'),
            'color' => $request->input('tag_color'),
            'created_at' => now()->toISOString(),
        ];
        
        UserSetting::setSetting($user->id, 'custom_tags', $customTags);
        
        return redirect()->route('settings.index')->with('success', 'Tag "' . $tagName . '" created successfully!');
    }

    public function updateTag(Request $request, $tag)
    {
        $request->validate([
            'new_name' => 'required|string|max:50|regex:/^[a-zA-Z0-9\s\-_]+$/',
            'new_color' => 'required|string|regex:/^#[0-9A-F]{6}$/i',
        ]);

        $user = Auth::user();
        $newName = trim($request->input('new_name'));
        $newColor = $request->input('new_color');
        
        // Get current custom tags
        $customTags = UserSetting::getSetting($user->id, 'custom_tags', []);
        
        // Check if new name already exists (excluding current tag)
        if (isset($customTags[$newName]) && $newName !== $tag) {
            return redirect()->route('settings.index')->withErrors(['new_name' => 'This tag name already exists.']);
        }
        
        // Check if tag exists
        if (!isset($customTags[$tag])) {
            return redirect()->route('settings.index')->withErrors(['new_name' => 'Tag not found.']);
        }
        
        // Update transactions that use this tag
        Transaction::where('user_id', $user->id)
            ->where('tag', $tag)
            ->update(['tag' => $newName]);
        
        // Update the tag
        $tagInfo = $customTags[$tag];
        unset($customTags[$tag]);
        $customTags[$newName] = $tagInfo;
        $customTags[$newName]['color'] = $newColor;
        
        // Save updated tags
        UserSetting::setSetting($user->id, 'custom_tags', $customTags);
        
        return redirect()->route('settings.index')->with('success', 'Tag "' . $tag . '" updated to "' . $newName . '" successfully!');
    }

    public function deleteTag(Request $request, $tag)
    {
        $user = Auth::user();
        
        // Check if tag is being used in transactions
        $transactionCount = Transaction::where('user_id', $user->id)
            ->where('tag', $tag)
            ->count();
        
        if ($transactionCount > 0) {
            return redirect()->route('settings.index')->withErrors(['tag' => 'Cannot delete tag "' . $tag . '" because it is being used in ' . $transactionCount . ' transaction(s).']);
        }
        
        // Remove from custom tags
        $customTags = UserSetting::getSetting($user->id, 'custom_tags', []);
        if (isset($customTags[$tag])) {
            unset($customTags[$tag]);
            UserSetting::setSetting($user->id, 'custom_tags', $customTags);
        }
        
        return redirect()->route('settings.index')->with('success', 'Tag "' . $tag . '" deleted successfully!');
    }
}