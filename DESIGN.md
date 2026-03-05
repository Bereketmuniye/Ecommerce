# Ethiopian Traditional Medicine Platform - System Architecture & Product Design

## 1. Recommended Technology Stack
To ensure a modern, fast, SEO-friendly, and secure application that can serve both local and international audiences, I recommend the following tech stack:

- **Frontend:** **Next.js (React)** - Next.js provides Server-Side Rendering (SSR) and Static Site Generation (SSG), which are absolutely critical for SEO. It also ensures fast page loads globally.
- **Backend:** **Laravel (PHP)** - Provides a robust, secure, and modern backend. It excels at building REST APIs, admin dashboards, and integrating multiple payment gateways like Stripe, PayPal, and Chapa.
- **Database:** **MySQL 8.x** or **PostgreSQL** - Both are excellent. MySQL is widely supported and works seamlessly with Laravel.
- **Caching & Queues:** **Redis** - Essential for fast data retrieval (e.g., caching plant dictionaries and blog posts) and processing background jobs (e.g., sending email invoices).
- **Media Storage & CDN:** **AWS S3** or **Cloudinary** - For storing images and embedded videos, optimized and delivered globally via a CDN.
- **Styling:** **Tailwind CSS** - For rapid UI development with a custom, premium aesthetic inspired by traditional Ethiopian colors (rich greens, golds, and earthy tones).

---

## 2. Full Feature List
### 🌿 Medical Content & Library
- **Medicinal Plants Directory:** Comprehensive profiles for plants, searchable by local and scientific names.
- **Bilingual Support:** Full English and Amharic support across the entire platform.
- **Blog & Education:** Categorized articles about treatments, traditions, and general health.
- **Video Hub:** A dedicated section for YouTube embeds and uploaded videos, categorized by health topics.

### 📚 E-commerce (Book Store)
- **Digital & Physical Books:** Delivery of digital PDFs via secure download links, and order tracking for physical books.
- **Multi-Currency Pricing:** Dynamic pricing in USD for international users and ETB for locals.
- **Payment Gateways:**
  - *Chapa:* For Ethiopian customers (Telebirr, CBE Birr, M-Pesa).
  - *Stripe / PayPal:* For international customers paying with credit cards.

### ⚙️ Admin Dashboard
- **Content Management System (CMS):** Fully-featured WYSIWYG editor for blogs and plant profiles.
- **Order Management:** Track book sales, digital downloads, and revenue analytics.
- **SEO Management:** Meta tags, slugs, and alt text configurations for every public page.

### ⭐ Extras
- Newsletter subscription integration (Mailchimp/Brevo).
- Article comments and moderation system.
- Curated User Testimonials.

---

## 3. Database Schema

Here is the core relational database structure to support the platform:

```sql
-- Users (Admins and Customers)
CREATE TABLE users (
    id BIGINT PRIMARY KEY,
    name VARCHAR(255),
    email VARCHAR(255) UNIQUE,
    password VARCHAR(255),
    role ENUM('admin', 'customer') DEFAULT 'customer',
    created_at TIMESTAMP
);

-- Medicinal Plants
CREATE TABLE plants (
    id BIGINT PRIMARY KEY,
    slug VARCHAR(255) UNIQUE,
    scientific_name VARCHAR(255),
    local_name_en VARCHAR(255),
    local_name_am VARCHAR(255),
    description_en TEXT,
    description_am TEXT,
    uses_en TEXT,
    uses_am TEXT,
    preparation_en TEXT,
    preparation_am TEXT,
    region VARCHAR(255),
    safety_info_en TEXT,
    safety_info_am TEXT,
    cover_image VARCHAR(255),
    image_gallery JSON,
    created_at TIMESTAMP
);

-- Articles / Blog
CREATE TABLE articles (
    id BIGINT PRIMARY KEY,
    slug VARCHAR(255) UNIQUE,
    category VARCHAR(255),
    title_en VARCHAR(255),
    title_am VARCHAR(255),
    content_en LONGTEXT,
    content_am LONGTEXT,
    cover_image VARCHAR(255),
    seo_title VARCHAR(255),
    seo_description TEXT,
    status ENUM('draft', 'published'),
    published_at TIMESTAMP
);

-- Videos
CREATE TABLE videos (
    id BIGINT PRIMARY KEY,
    topic VARCHAR(255),
    title_en VARCHAR(255),
    title_am VARCHAR(255),
    youtube_url VARCHAR(255),
    created_at TIMESTAMP
);

-- Books
CREATE TABLE books (
    id BIGINT PRIMARY KEY,
    slug VARCHAR(255) UNIQUE,
    title_en VARCHAR(255),
    title_am VARCHAR(255),
    description_en TEXT,
    description_am TEXT,
    price_usd DECIMAL(8,2), -- e.g. 19.99
    price_etb DECIMAL(8,2), -- e.g. 1500.00
    type ENUM('digital', 'physical', 'both'),
    cover_image VARCHAR(255),
    digital_file_url VARCHAR(255) NULL,
    created_at TIMESTAMP
);

-- Orders
CREATE TABLE orders (
    id BIGINT PRIMARY KEY,
    user_id BIGINT,
    total_usd DECIMAL(8,2),
    total_etb DECIMAL(8,2),
    currency ENUM('USD', 'ETB'),
    payment_method ENUM('stripe', 'paypal', 'chapa'),
    payment_status ENUM('pending', 'paid', 'failed'),
    fulfillment_status ENUM('pending', 'delivered'),
    created_at TIMESTAMP,
    FOREIGN KEY (user_id) REFERENCES users(id)
);

-- Order Items
CREATE TABLE order_items (
    id BIGINT PRIMARY KEY,
    order_id BIGINT,
    book_id BIGINT,
    quantity INT,
    price DECIMAL(8,2),
    FOREIGN KEY (order_id) REFERENCES orders(id),
    FOREIGN KEY (book_id) REFERENCES books(id)
);
```

---

## 4. API Structure (REST API)

The Laravel backend will expose a RESTful JSON API to the Next.js frontend:

**Public Endpoints:**
- `GET /api/v1/plants` - List medicinals plants (with pagination, search, filter)
- `GET /api/v1/plants/{slug}` - Get specific plant details
- `GET /api/v1/articles` - List blog posts
- `GET /api/v1/articles/{slug}` - Get specific article
- `GET /api/v1/videos` - List YouTube educational videos
- `GET /api/v1/books` - List available books
- `GET /api/v1/books/{slug}` - Book details

**Authentication & Sales Endpoints:**
- `POST /api/v1/auth/register` - Create customer account
- `POST /api/v1/auth/login` - Issue JWT or Sanctum token
- `POST /api/v1/checkout/intent` - Initialize Stripe or Chapa checkout session
- `POST /api/v1/webhooks/chapa` - Webhook to confirm Ethiopian local payments
- `POST /api/v1/webhooks/stripe` - Webhook to confirm International payments

**Admin Endpoints (Protected):**
- `POST /api/v1/admin/plants` - Create a new plant entry
- `PUT /api/v1/admin/books/{id}` - Update book inventory/details
- `GET /api/v1/admin/analytics` - Dashboard metrics (sales, views)

---

## 5. UI Page Structure

1. **Home:**
   - Hero Section (Expert introduction, high-quality video or image background).
   - "Featured Medicinal Plants" carousel.
   - "Latest from the Blog" and "Recent Videos".
   - Call to Action (CTA) linking to the Book Store.
   - Social Media Feed (Live Instagram/TikTok integration).

2. **About the Expert:**
   - Professional biography, credentials, history in traditional healing.
   - Mission statement and vision.
   - Testimonials/Success Stories.

3. **Medicinal Plants Directory:**
   - Grid layout of plants.
   - Advanced Search bar (by local name, scientific name, or illness parameter).
   - Detail Pages: Large image gallery, Amharic/English toggle, preparation instructions, warnings (highlighted).

4. **Blog / Education:**
   - Masonry or Grid layout of articles.
   - Categories sidebar (e.g., Immunity, Skin Care, Diet).
   - Article Pages: Rich text, author bio, social share buttons, comment section.

5. **Videos Hub:**
   - Embedded YouTube player with custom, branded thumbnails.
   - Filterable tabs by topic.

6. **Book Store:**
   - E-commerce showcase layout.
   - Product Page: Book 3D mockup, synopsis, reviews, "Buy Digital" vs "Buy Physical" toggle, Add to Cart / Buy Now buttons.
   - Seamless checkout page with localized currency (ETB vs USD based on IP or language).

7. **Contact & Socials:**
   - Direct links to YouTube, Facebook, TikTok, Telegram.
   - Secure Contact Form.
   - Newsletter signup footer on every page.

---

## 6. Monetization Strategy
1. **Direct E-commerce Sales:** High-margin sales of digital eBooks globally with zero shipping costs.
2. **Physical Book Sales:** Local delivery within Ethiopia (paid via Chapa/Telebirr) and specialized international shipping options.
3. **YouTube Monetization Loop:** Embedding videos on the site drives views and engagement directly to the expert's YouTube channel, increasing AdSense revenue.
4. **Premium Consultations (Future Expansion):** Sell 1-on-1 virtual consultation sessions via Zoom, integrated into the e-commerce cart.

---

## 7. SEO Strategy
- **Bilingual SEO:** Implement unique URLs for languages (e.g., `domain.com/en/plants/garlic` and `domain.com/am/plants/nech-shinkurt`). Use `hreflang` tags.
- **Structured Data (Schema.org):** Implement `Product` schema for books, `Article` schema for blog posts, and `MedicalWebPage` schema specifically to outline traditional remedies properties.
- **Server-Side Rendering (SSR):** Next.js will pre-render HTML, making it instantly crawlable by Googlebot.
- **Image Optimization:** Auto-convert images to WebP format, serving responsive sizes (via Next/Image or Cloudinary) for fast load times (Core Web Vitals).

---

## 8. Security Considerations
- **Data Integrity:** Strict validation on all user inputs, preventing SQL injection and XSS via Laravel's built-in protections.
- **Medical Disclaimer:** A persistent, non-intrusive banner and clear warnings on plant pages indicating the content is educational and not a substitute for professional medical advice.
- **Payment Security:** No storing of credit card data. All payments tokenized and processed strictly through Stripe/PayPal/Chapa PCI-compliant APIs.
- **Admin Access:** Two-Factor Authentication (2FA) enforced for the Admin dashboard to protect content integrity and customer order data.
