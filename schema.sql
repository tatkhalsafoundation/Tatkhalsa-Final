-- Blog System Schema

-- Enable UUID extension
CREATE EXTENSION IF NOT EXISTS "uuid-ossp";

-- Table: authors
CREATE TABLE authors (
    id UUID PRIMARY KEY DEFAULT uuid_generate_v4(),
    name VARCHAR(255) NOT NULL,
    avatar_url TEXT,
    bio TEXT,
    created_at TIMESTAMP WITH TIME ZONE DEFAULT TIMEZONE('utc', NOW())
);

-- Table: blog_categories
CREATE TABLE blog_categories (
    id UUID PRIMARY KEY DEFAULT uuid_generate_v4(),
    name VARCHAR(255) NOT NULL UNIQUE,
    slug VARCHAR(255) NOT NULL UNIQUE,
    created_at TIMESTAMP WITH TIME ZONE DEFAULT TIMEZONE('utc', NOW())
);

-- Table: blog_posts
CREATE TABLE blog_posts (
    id UUID PRIMARY KEY DEFAULT uuid_generate_v4(),
    title VARCHAR(255) NOT NULL,
    slug VARCHAR(255) NOT NULL UNIQUE,
    excerpt TEXT,
    content TEXT NOT NULL,
    featured_image TEXT,
    author_id UUID REFERENCES authors(id) ON DELETE SET NULL,
    category_id UUID REFERENCES blog_categories(id) ON DELETE SET NULL,
    is_published BOOLEAN DEFAULT FALSE,
    published_at TIMESTAMP WITH TIME ZONE,
    created_at TIMESTAMP WITH TIME ZONE DEFAULT TIMEZONE('utc', NOW()),
    updated_at TIMESTAMP WITH TIME ZONE DEFAULT TIMEZONE('utc', NOW())
);

-- Table: website_data (For dynamic general website content)
CREATE TABLE website_data (
    id UUID PRIMARY KEY DEFAULT uuid_generate_v4(),
    section_key VARCHAR(100) NOT NULL UNIQUE,
    content JSONB NOT NULL,
    updated_at TIMESTAMP WITH TIME ZONE DEFAULT TIMEZONE('utc', NOW())
);

-- Setup Row Level Security (RLS)
ALTER TABLE authors ENABLE ROW LEVEL SECURITY;
ALTER TABLE blog_categories ENABLE ROW LEVEL SECURITY;
ALTER TABLE blog_posts ENABLE ROW LEVEL SECURITY;
ALTER TABLE website_data ENABLE ROW LEVEL SECURITY;

-- Create policies for public reading
CREATE POLICY "Public profiles are viewable by everyone." ON authors FOR SELECT USING (true);
CREATE POLICY "Categories are viewable by everyone." ON blog_categories FOR SELECT USING (true);
CREATE POLICY "Published posts are viewable by everyone." ON blog_posts FOR SELECT USING (is_published = true);
CREATE POLICY "Website content is viewable by everyone." ON website_data FOR SELECT USING (true);

-- Insert initial dummy data for website content
INSERT INTO website_data (section_key, content) VALUES
('hero_section', '{"title": "Serving Humanity Through Seva, Compassion, and Community Action", "subtitle": "Tatkhalsa Foundation is a registered non-profit organization dedicated to humanitarian relief..."}'::jsonb),
('stats', '{"livesImpacted": 5000, "bloodDonors": 100, "initiatives": 50, "volunteers": 100}'::jsonb);
