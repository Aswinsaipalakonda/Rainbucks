# Admin Instructions for Dynamic Course Page Updates

## Database Setup

1. **Run the SQL migration** to add new content fields to the packages table:
   ```sql
   -- Execute the file: includes/add_package_content_fields.sql
   ```

2. **Upload the constant image** for the "Why Choose" section:
   - Replace `assets/images/why-choose-constant.jpg` with an actual image
   - Recommended size: 600x400 pixels
   - Content: People learning, teamwork, professional environment

## Package Content Management

The admin can now edit two new sections for each package:

### 1. Course Overview Section
- **Overview Title**: Custom title for the overview section
- **Overview Content**: Main description text (supports line breaks)
- **What You'll Learn**: List of learning outcomes (one per line)

### 2. Why Choose Section  
- **Why Choose Title**: Custom title for this section
- **Why Choose Content**: Main description text (supports line breaks)
- **Why Choose Points**: List of benefits/reasons (one per line)

## How to Edit Package Content

1. Go to **Admin Panel > Packages**
2. Click **Edit** on any package
3. Scroll down to find the new sections:
   - **Course Overview Section**
   - **Why Choose Section**
4. Fill in the content fields
5. Click **Update Package**

## Content Guidelines

- **Titles**: Keep them concise and engaging
- **Content**: Write in a conversational, professional tone
- **Lists**: Use one item per line for proper formatting
- **Length**: Aim for 2-3 paragraphs for content sections

## Image Requirements

- **Package Image**: Shows in the hero section frame
- **Why Choose Image**: Constant image at `assets/images/why-choose-constant.jpg`
- Both images will be displayed with decorative frames and green accent dots

## Features

- ✅ Responsive design for all devices
- ✅ Decorative frames around images
- ✅ Professional styling matching the brand
- ✅ Dynamic content from database
- ✅ Easy admin management
