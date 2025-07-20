<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use App\Models\UserSetting;
use App\Models\Transaction;

class SettingsController extends Controller
{
    public function index()
    {
        $user = Auth::user();
        
        // Get current tag colors
        $customTagColors = UserSetting::getSetting($user->id, 'tag_colors', []);
        
        // Get custom tags
        $customTags = UserSetting::getSetting($user->id, 'custom_tags', []);
        
        // Define default colors
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
        
        // Add custom tags to colors
        foreach ($customTags as $tag => $tagInfo) {
            $defaultColors[$tag] = $tagInfo['color'];
        }
        
        // Add predefined tags with their hex colors
        $predefinedTags = ['Salary', 'Freelance', 'Investment', 'Gift', 'Bonus', 'Other', 'Food', 'Transportation', 'Housing', 'Utilities', 'Entertainment', 'Healthcare', 'Shopping', 'Education'];
        foreach ($predefinedTags as $tag) {
            if (!isset($defaultColors[$tag])) {
                $defaultColors[$tag] = \App\Models\Transaction::getTagHexColor($tag, $user->id);
            }
        }
        
        $allTagColors = array_merge($defaultColors, $customTagColors);
        
        // Organize tags by type for unified display
        $incomeTags = [];
        $expenseTags = [];
        
        // Add default income tags
        $defaultIncomeTags = ['Salary', 'Freelance', 'Investment', 'Gift', 'Bonus', 'Other'];
        foreach ($defaultIncomeTags as $tag) {
            $incomeTags[$tag] = [
                'name' => $tag,
                'color' => $allTagColors[$tag] ?? '#6c757d',
                'is_custom' => false,
                'type' => 'income'
            ];
        }
        
        // Add default expense tags
        $defaultExpenseTags = ['Food', 'Transportation', 'Housing', 'Utilities', 'Entertainment', 'Healthcare', 'Shopping', 'Education', 'Other'];
        foreach ($defaultExpenseTags as $tag) {
            $expenseTags[$tag] = [
                'name' => $tag,
                'color' => $allTagColors[$tag] ?? '#6c757d',
                'is_custom' => false,
                'type' => 'expense'
            ];
        }
        
        // Add custom tags to their respective categories
        foreach ($customTags as $tag => $tagInfo) {
            $tagData = [
                'name' => $tag,
                'color' => $tagInfo['color'],
                'is_custom' => true,
                'type' => $tagInfo['type']
            ];
            
            if ($tagInfo['type'] === 'income') {
                $incomeTags[$tag] = $tagData;
            } else {
                $expenseTags[$tag] = $tagData;
            }
        }
        
        return view('settings', [
            'tagColors' => $allTagColors,
            'defaultColors' => $defaultColors,
            'customColors' => $customTagColors,
            'customTags' => $customTags,
            'incomeTags' => $incomeTags,
            'expenseTags' => $expenseTags,
        ]);
    }

    public function updateTagColors(Request $request)
    {
        $request->validate([
            'tag_colors' => 'required|array',
            'tag_colors.*' => 'string|regex:/^#[0-9A-F]{6}$/i',
        ]);

        $user = Auth::user();
        $customColors = $request->input('tag_colors');
        
        // Only save non-default colors
        $defaultColors = [
            'Salary' => '#198754',
            'Freelance' => '#0dcaf0',
            'Investment' => '#0d6efd',
            'Gift' => '#ffc107',
            'Bonus' => '#198754',
            'Other' => '#6c757d',
            'Food' => '#dc3545',
            'Transportation' => '#0d6efd',
            'Housing' => '#212529',
            'Utilities' => '#0dcaf0',
            'Entertainment' => '#ffc107',
            'Healthcare' => '#dc3545',
            'Shopping' => '#0d6efd',
            'Education' => '#0dcaf0',
        ];
        
        $filteredColors = [];
        
        foreach ($customColors as $tag => $color) {
            if (!isset($defaultColors[$tag]) || $defaultColors[$tag] !== $color) {
                $filteredColors[$tag] = $color;
            }
        }
        
        UserSetting::setSetting($user->id, 'tag_colors', $filteredColors);
        
        return redirect()->route('settings.index')->with('success', 'Tag colors updated successfully!');
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
        
        // Check if tag already exists (either predefined or custom)
        $predefinedTags = ['Salary', 'Freelance', 'Investment', 'Gift', 'Bonus', 'Other', 'Food', 'Transportation', 'Housing', 'Utilities', 'Entertainment', 'Healthcare', 'Shopping', 'Education'];
        
        if (in_array($tagName, $predefinedTags)) {
            return redirect()->route('settings.index')->withErrors(['tag_name' => 'This tag name already exists as a predefined tag.']);
        }
        
        // Get existing custom tags
        $customTags = UserSetting::getSetting($user->id, 'custom_tags', []);
        
        // Check if custom tag already exists
        if (isset($customTags[$tagName])) {
            return redirect()->route('settings.index')->withErrors(['tag_name' => 'This custom tag already exists.']);
        }
        
        // Add new custom tag
        $customTags[$tagName] = [
            'type' => $request->input('tag_type'),
            'color' => $request->input('tag_color'),
            'created_at' => now()->toISOString(),
        ];
        
        UserSetting::setSetting($user->id, 'custom_tags', $customTags);
        
        return redirect()->route('settings.index')->with('success', 'Custom tag "' . $tagName . '" created successfully!');
    }

    public function deleteCustomTag(Request $request)
    {
        $request->validate([
            'tag_name' => 'required|string',
        ]);

        $user = Auth::user();
        $tagName = $request->input('tag_name');
        
        // Get existing custom tags
        $customTags = UserSetting::getSetting($user->id, 'custom_tags', []);
        
        // Check if tag exists
        if (!isset($customTags[$tagName])) {
            return redirect()->route('settings.index')->withErrors(['tag_name' => 'Custom tag not found.']);
        }
        
        // Check if tag is being used in transactions
        $transactionCount = Transaction::where('user_id', $user->id)
            ->where('tag', $tagName)
            ->count();
        
        if ($transactionCount > 0) {
            return redirect()->route('settings.index')->withErrors(['tag_name' => 'Cannot delete tag "' . $tagName . '" because it is being used in ' . $transactionCount . ' transaction(s).']);
        }
        
        // Remove the tag
        unset($customTags[$tagName]);
        UserSetting::setSetting($user->id, 'custom_tags', $customTags);
        
        // Also remove from tag colors if it exists there
        $customTagColors = UserSetting::getSetting($user->id, 'tag_colors', []);
        if (isset($customTagColors[$tagName])) {
            unset($customTagColors[$tagName]);
            UserSetting::setSetting($user->id, 'tag_colors', $customTagColors);
        }
        
        return redirect()->route('settings.index')->with('success', 'Custom tag "' . $tagName . '" deleted successfully!');
    }
}