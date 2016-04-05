                                         __   .__         .__   
  ____    ______  ______  ____    ____ _/  |_ |__|_____   |  |  
_/ __ \  /  ___/ /  ___/_/ __ \  /    \\   __\|  |\__  \  |  |  
\  ___/  \___ \  \___ \ \  ___/ |   |  \|  |  |  | / __ \_|  |__
 \___  >/____  >/____  > \___  >|___|  /|__|  |__|(____  /|____/
     \/      \/      \/      \/      \/                \/       
  __   .__                              
_/  |_ |  |__    ____    _____    ____  
\   __\|  |  \ _/ __ \  /     \ _/ __ \ 
 |  |  |   Y  \\  ___/ |  Y Y  \\  ___/ 
 |__|  |___|  / \___  >|__|_|  / \___  >
 
 
 
Essential WooCommerce Theme v1.0

Created: 22/08/2013 
Last Updated: 10/10/2013 
By: Prospekt Solutions 
Email: prospekt@prospekt.hr

Thank you for purchasing our theme. If you have any questions that are beyond the scope of this help file, please feel free to email via contact form here. Happy selling!

Online documentation: http://essential.prospekt-solutions.com/documentation/
Contact: http://themeforest.net/user/ProspektDesign


Table of Contents

    1. Installation and Setup
          + Removing theme admin dashboard widget
    2. General Options
    3. Home page Setup
          + Home page Content
          + Home page Slider
          + Home page Widgets
          + Home page Footer
    4. Menu Setup
          + Creating Menu Items
          + Ordering Menu
    5. Pages
          + Creating a page
    6. Blog / News Page
          + Creating Page for Blog listing
          + Creating posts
    7. Shortcodes
    8. Frequently Asked Questions - FAQ
    9. PSD Files
     __________________________________________________________________


Installation and Setup


   Since Essential is WooCommerce eCommerce theme prerequisite is
   Wordpress [24]WooCommerce plugin which can be downloaded [25]here for
   free. Once plugin is installed and activated, download Essential theme
   package and unzip it, then go to theme folder, unzip essential.zip
   folder and upload to wp-content/themes/ folder of your Wordpress site.
   You can also use Wordpress install feature, just go to Install Themes
   tab under Appearance => Themes section, select upload and browse
   essential.zip within theme folder, Wordpress will install necessary
   files in coresponding place. Theme styles WooCommerce elements and
   widgets via CSS.

   After theme is installed you need to go to Appearance => Themes menu
   and activate Essential theme.

   If you want quick setup for your website while developing, you can
   import Woocommerce xml data sample that is included in theme package
   located in wp-content/plugins/woocommerce/dummy_data.xml file, just
   follow these steps to import sample data:
    1. Login to WP-admin
    2. Go to Tools => Import menu and choose Wordpress
    3. In upload field choose the xml sample data and upload it
    4. Click on Import Attachments - Download and import file attachments
       checkbox (make sure that Woocommerce is installed and activated
       before importing dummy data)
    5. Set couple features items
    6. Now you need to configure Essential theme pages, options and create
       menus - check steps below

   Initial theme configuration is usually done by following steps below:
    1. Login to WP-admin
    2. Activate theme
    3. By default theme shows latest blog posts. That's why you need to
       create new page called home page, select home page template, save
       it and go to Settings => Reading and set that home page for Front
       page displays in order to get our custom home page shown. Now you
       can click on Essential options to proceed with customization of
       defaults like number of products, default slider, custom CSS, etc
    4. Create pages for contact, wishlist and compare - this is done by
       creating new page and setting appropriate template, no content
       should be added except for contact page where you can edit map and
       contact info, however contact page has to be created first and then
       addded as contact page in Essential options => General => Contact
       page, after contact page is set and options saved you will now see
       additional custom fields for location info (3 maps are on contact
       page by default), contact info, texts, etc. If you add content for
       contact page that text will be shown on the left side of contact
       form.
    5. Now if you want to change color scheme and styles, add some
       background images do it on Essential options => General.
    6. Next step is to go to Essential options => Home page, there you can
       edit tagline in Home header text.
    7. Select some product categories you want to show on home page right
       under tagline by adding them to Product Category list, under this
       input you can check Use tabs checkbox if you want to have products
       displayed in tabs and you can change number of products that you
       want to show and list.
    8. Next thing to do is to add Home footer heading, Home footer link,
       Home footer text and Home footer image this block is meant for
       footer info where you can put shipping / payment details, link to
       your contact page, quick facts, etc. Home footer image dimensions
       have to be right dimensions - 457px x 237px. Since Wordpress crops
       all images to all dimensions we did not want to add this one for
       single image in order to have more optimal theme, save you from
       using unnecessary additional disk space and processing time.
    9. You can now proceed with default slider setup, go to Essential
       options => Content slider and configure what and how do you want to
       slide, make sure to have some content with images in order to have
       something to slide. This is configuration for default slider so you
       do not have to define slider in every post, page or product.
   10. Next step is to add main menu, first create menu in Appearance =>
       Menus named "main menu", add some items to it and save it. Then go
       to Appearance => Menus => Manage locations and add that menu to
       locations named "Primary Menu" and "Mobile Menu". Top menu is menu
       location above main menu, where search is. Usually there are links
       to my account, cart (and cart dropdown) and checkout.
   11. Make sure that file
       wp-content/themes/essential/admin/fonts/fonts.json is world
       writable (has 666 permissions) in order to have latest Google fonts
       fetched and cached
   12. If you want custom CSS to be in file and not inline make sure that
       file wp-content/themes/essential/css/custom.css is world writable
       (has 666 permissions)

   Essential theme is a responsive theme. That means that it should work
   on mobile devices and tablets without any modifications. Theme will
   adapt to different screen width automatically, blocks will be resized
   and reordered according to current viewport width.

Removing theme admin dashboard widget


   We have added admin dashboard so users have links to documentation and
   support at hand. If you want to remove that widget open file
   essential/admin/admin-function.php and replace
   add_action('wp_dashboard_setup', 'add_dashboard_widgets' ); with
   //add_action('wp_dashboard_setup', 'add_dashboard_widgets' ); - so just
   comment out that function.

General Options
 

   After you finished installing Essential theme, beside WooCommerce menus
   (WooCommerce and Products) two new menu items will appear in Wordpress
   admin menu:
    1. Essential Theme Options
    2. Slides

   In Essential Theme Options you can change and modify settings, colors,
   background image, fonts, headers, footer text, set contact page, change
   settings for Home page, default Content slider and add custom CSS code.
   Slides is basically custom post type which allows you to add custom
   slides to main home page slider. Home page slider is extremly
   customizable - if you go to edit home page and uncheck "use default
   slider" you will get all available slider options. You can slide any
   type of post or page, whole category, tag, products category, product
   tag, specific post or custom made slide.

Theme Options - General

   Here you can define and modify color scheme, background image (or
   color), fonts, footer text, select contact, wishlist and compare page.
   Most of the available options are self explainable. Post header
   background is an option where you can use CSS3 effects to create
   gradients using CSS3 utility "Gradient Creator". You can change premade
   contact page which is selected by default (to change email to which
   emails are sent you need to go to Pages => All Pages, find page named
   "Contact" and click edit. Scroll down to Contact page options).
   Wishlist and compare pages should stay as is unless you know what are
   you doing (their templates can be changed by editing files wishlist.php
   and compare.php).

Theme options - Home page

   Essential Theme allows you to use two types of home page, key
   difference is in slider type and how and how many products you want to
   show on it. To configure those settings you have Essential Options =>
   Home page and Essential Options => Content slider settings. Home page
   can show products using tabbed slider or in separate blocks. To show
   product in separate blocks one under another please uncheck "Use tabs"
   checkbox.

Theme options - Content slider

Theme options - Custom CSS

   Custom CSS feature has been added so you can conveniently override
   theme's css by rewriting its classes or adding your custom css code. To
   find out which class you want to edit you can use developer tools like
   Firebug or Chrome's dev tools (just hit F12 to open it, or right click
   and then "inspect element"). Custom CSS can be added inline or in file
   essential/css/custom.css - if you select "use file" in Essential
   Options => General make sure to make file world writable (set
   permissions to 666).
     __________________________________________________________________

Home page Setup

     * Home page Content By default theme shows latest blog posts that's
       why you need to have page with home page template. Once you have
       that in place you can click on Essential options to proceed with
       customization of defaults like number of products, default slider,
       custom CSS, etc. To modify home page settings you need to click on
       Essential Theme Options => Home page tab. Settings are mostly self
       explainable by their name and description. Tagline can be changed
       there along with featured product categories. Product Category list
       allows you to easily manage featured product categories on home
       page.
       Featured items are products that can be selected by going to
       Products => Products list and clicking on star which toggles
       featured status for a product. Featured items are sorted by date.
       Continue reading for home page slider setup and options.
     * Home page Slider
       Home page has big slider in header area, in fact every page or
       product page can have slider in header. You can add custom slides
       by going to Slides => Add slide, or you can select various content
       for slider in Essential Theme Options => Content slider - in this
       section you define default slider settings so if you checked "Use
       theme default slider settings" in your home page. You can also
       customize that slider by unchecking "Use theme default slider
       settings" - for example you can add custom post type, whole
       category or tag, product category or tag, specific post or you can
       write custom query args to fetch slider content. Content can be
       sorted asc / desc by date, title, author, last modified date,
       random.
       There are two types of sliders - default and simple. Default is a
       bit more advanced, it has parallax effect while simple is, like its
       name says, a bit simpler. Simple slider has image, heading and
       subheading text and sliding items. Keep on your mind that slider
       image is not sliding and its purpose is more like a slider
       background. You can slide products, posts, tags etc. It's important
       that objects you want to slide have image set.
     * Home page Widgets
       Home page itself does not have widget space. Widgets can be added
       to footer (8 locations named Footer 1 to Footer 8) and in pages
       with sidebar location named Primary. Sidebar can be located on left
       or right, or page can be completely without sidebar.
     * Home page Footer
       Home page has a place for content where you can add content like
       about us, quick facts, payment and shipping info... This content
       can be edited by going to Essential Options => Home page tab. You
       will see there inputs named Home footer heading, Home footer link,
       Home footer text and Home footer Image. Footer image is not cropped
       by Wordpress so make sure to upload exact dimensions - 457px x
       237px. Explanation why image is not cropped is on the top of
       documentation, in theme setup section.
     __________________________________________________________________

Menu Setup

     * Creating Menu Items
       To create, modify or delete Menu items go to Appearance => Menu.
       WordPress menu editor is easy to use just drag n drop menu items to
       right position after you selected desired menu.
       First make sure that you have enabled in screen options every type
       of menu item you want to add and CSS classes. Then select menu you
       want to edit, you will see main menu and shipping and FAQ menu. If
       you want to create mega menu you will need to add CSS class to top
       level item named "megamenu".
       When creating a submenu or megamenu make sure to either enter real
       url for parent element so WordPress can mark it as current or use
       '#' as placeholder if you don't want item to be marked current.
     * Ordering Menu
       To order the menu items, you just need to drag and drop item in
       menu manager, you can also place and ordering the menu items as
       your child menu.
     __________________________________________________________________

Pages

     * Create a page
       Creating a page is simple and easy. First make sure that you have
       enabled all Screen Options. Then click on Pages => Add New to start
       with new page creation. You will notice 3 different layouts which
       you can choose for your pages. Beside that there are three page
       templates - Contact, Compare and Wishlist templates. Those
       templates are intended for our predefined pages.
       Each page can have custom header background, custom slider and
       featured image. You can use [31]shortcodes in your pages (and
       posts).
     * Contact page
       Contact page has special template applied since its layout is a bit
       different. To edit Contact page click on Pages => All Pages, find
       Contact page and then click "edit". Enable "Contact page options"
       in Screen Options to get fields for input map code, contact email
       and address - see image below. Make sure to leave layout set to
       default, with no slider and no background image.
     __________________________________________________________________


Blog / News 

     * Creating Page for Blog or News listing
       First you need to define category in Posts => Categories, you can
       name that category for example "news" or "blog".
       Once this is done just add this category to a menu via WordPress
       menu editor - Appearance => Menu.
     * Creating posts To add new post item, click Posts => Add new, write
       something and select category you defined before. Procedure for
       adding new post items (or news items) is simple and
       straightforward:
         1. Enter your title for the post
         2. Add some text for your post
         3. Assign the post item to Blog Category
         4. Assign Tags to post item
         5. Assign Featured image to post item which will be shown in post
            archive and single post view
     __________________________________________________________________

Shortcodes

   Essential implements shortcodes which can simplify content creating and
   editing.
    1. Box [box]sample text[/box] allows you to box content inside .box
       class
    2. Two columns [two-column]column 1
       text[/two-column][two-column-last]column 2 text[/two-column-last]
       allows you to split content in two columns
    3. Three columns [three-column]column 1
       text[/three-column][three-column]column 2
       text[/three-column][three-column-last]column 3
       text[/three-column-last] allows you to split content in three
       columns
    4. Four columns [four-column]column 1
       text[/four-column][four-column]column 2
       text[/four-column][four-column]column 3
       text[/four-column][four-column-last]column 4
       text[/four-column-last] allows you to split content in four columns
    5. Small button [small_button url=google.com]Small
       button[/small_button][small_button url=google.com
       color=orange]Small button[/small_button] create small button with
       link (two types available - regular and orange)
    6. Regular button [button url=google.com]Normal Button[/button][button
       url=google.com color=orange]Normal Button[/button] create regular
       button with link (two types available - regular and orange)
    7. Big button [big_button url=google.com]Big
       button[/big_button][big_button url=google.com color=orange]Big
       button[/big_button] create big button with link (two types
       available - regular and orange)
    8. Icons [icon character=??] [icon character=?? size=2em] [icon
       character=?? size=3em color=red] create Entypo icons in different
       size and colors, more informations about Entypo icons can be found
       [34]here
    9. Embed YouTube video [youtube]YouTube video ID[/youtube] allows you
       to easily embed YouTube videos by adding video ID inside this
       shortcode
     Links: http://www.entypo.com

     __________________________________________________________________

Frequently Asked Questions - FAQ 

    1. How can I change contact email? - Go to Pages => All Pages, find
       page named "Contact" and click edit. Scroll down to Contact page
       options and there you will find field named "Send mail to".
    2. How can I change map on about us page? - Go to Pages => All Pages,
       find page named "Contact" and click edit. Scroll down to Contact
       page options and there you will find field named "Place map". Find
       your location on google maps, click on "link" and under "Paste HTML
       to embed in website" you will find iframe code which you need to
       copy paste in this field.
    3. I want sidebar on left / right side of a page? - Go to Pages => All
       Pages, find page you want to edit. Find in right sidebar box named
       "Layout". If you don't see that box click on "Screen Options" to
       enable it. There you can select layout with sidebar on left or
       right side.
    4. I want full width page? - Go to Pages => All Pages, find page you
       want to edit. Find in right sidebar box named "Layout". If you
       don't see that box click on "Screen Options" to enable it. There
       you can select layout named "full width".
     __________________________________________________________________

PSD Files

     * Following PSD files are included with theme:
         1. 01-Homepage.psd
         2. 02-Category.psd
         3. 03-Product.psd
         4. 03-Product-Review.psd
         5. 04-Product-compare.psd
         6. 05-Shopping-Cart.psd
         7. 06-CheckOut.psd
         8. 07-Aboutus.psd
         9. 08-Contact.psd
        10. 09-Blog.psd
        11. 09-Blog-post.psd
        12. 10-Quick-Cart.psd
        13. 11-Submenu.psd
     __________________________________________________________________

   Once again, thank you for purchasing our theme. We'd be glad to help
   you if you have any questions. If you have more general question
   related to our themes on ThemeForest, you might consider visiting the
   forums and post your question in the "Item Discussion" section.
