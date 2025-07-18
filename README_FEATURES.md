# Personal Finance Tracker - New Features

## Tag System

### Predefined Tags with Color Coding

The application now includes a comprehensive tag system with unique colors for each predefined tag:

**Income Tags:**

-   🟢 **Salary** (Green) - Regular employment income
-   🔵 **Freelance** (Blue) - Freelance or contract work
-   🔷 **Investment** (Primary Blue) - Investment returns
-   🟡 **Gift** (Yellow) - Gifts and presents
-   🟢 **Bonus** (Green) - Performance bonuses
-   ⚫ **Other** (Gray) - Other income sources

**Expense Tags:**

-   🔴 **Food** (Red) - Groceries and dining
-   🔷 **Transportation** (Primary Blue) - Gas, public transit, rideshare
-   ⚫ **Housing** (Dark) - Rent, mortgage, home expenses
-   🔵 **Utilities** (Blue) - Electricity, water, internet
-   🟡 **Entertainment** (Yellow) - Movies, games, hobbies
-   🔴 **Healthcare** (Red) - Medical expenses, insurance
-   🔷 **Shopping** (Primary Blue) - Clothing, electronics, etc.
-   🔵 **Education** (Blue) - Tuition, books, courses
-   ⚫ **Other** (Gray) - Other expenses

### How to Use Tags

1. **Selecting Predefined Tags:** When creating or editing a transaction, choose the transaction type first. The tag dropdown will automatically populate with relevant predefined tags for that type, each with its own color.

2. **Custom Tags:** If none of the predefined tags match your transaction, click the "Custom" button to enter your own tag. Custom tags will appear in gray.

3. **Dynamic Tag Options:** The available tags change based on whether you select "Income" or "Expense" as the transaction type.

4. **Visual Recognition:** Each tag has a unique color that helps you quickly identify transaction categories at a glance.

## Image Upload Feature

### Supported Features

-   **File Types:** JPEG, PNG, JPG, GIF
-   **File Size:** Maximum 2MB
-   **Purpose:** Upload bills, receipts, or any proof of transaction
-   **Optional:** Image upload is completely optional

### How to Use Image Upload

1. **Adding Images:** When creating or editing a transaction, use the file input field to select an image from your device.

2. **Viewing Images:** When editing a transaction that has an uploaded image, you'll see a thumbnail preview of the current image.

3. **Replacing Images:** When editing, you can upload a new image to replace the existing one. The old image will be automatically deleted.

4. **Image Storage:** Images are stored securely in the `storage/app/public/transaction-images/` directory and are accessible via the public storage link.

## Description Field Changes

-   **Optional:** The description field is now optional, allowing users to focus on tags for categorization.
-   **Fallback Display:** If no description is provided, the dashboard will show "No description" instead of an empty field.

## Database Changes

The following new fields have been added to the transactions table:

-   `tag` (string, nullable): Stores the transaction category/tag
-   `image_path` (string, nullable): Stores the path to uploaded images
-   `description` (string, nullable): Now optional instead of required

## Setup Instructions

1. **Run Migration:** Execute the migration to add new fields to the database:

    ```bash
    php artisan migrate
    ```

2. **Create Storage Link:** Ensure the storage link is created for image access:

    ```bash
    php artisan storage:link
    ```

3. **Update Existing Data:** Run the seeder to add tags to existing transactions:
    ```bash
    php artisan db:seed --class=TransactionSeeder
    ```

## Security Features

-   **File Validation:** Only image files are accepted with size limits
-   **User Isolation:** Users can only access and modify their own transactions
-   **Secure Storage:** Images are stored outside the web root for security
-   **Automatic Cleanup:** Old images are automatically deleted when replaced or when transactions are deleted

## Color System Benefits

-   **Quick Recognition:** Instantly identify transaction types by color
-   **Visual Organization:** Makes scanning transaction lists much easier
-   **Consistent Branding:** Each category has a memorable, consistent color
-   **Accessibility:** High contrast colors ensure good readability
