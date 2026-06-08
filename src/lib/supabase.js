import { createClient } from '@supabase/supabase-js'

const supabaseUrl = 'https://lajzwxdhqitwnnzilmiy.supabase.co'
const supabaseKey = 'sb_publishable_yAQ94zdle29CW-f6ooXObA_REZBtvOj'

export const supabase = createClient(supabaseUrl, supabaseKey)

// Helper to fetch blog posts
export async function getBlogPosts() {
  const { data, error } = await supabase
    .from('blog_posts')
    .select(`
      *,
      author:authors(name, avatar_url),
      category:blog_categories(name)
    `)
    .eq('is_published', true)
    .order('published_at', { ascending: false })
  
  if (error) {
    console.error("Error fetching blog posts:", error)
    return []
  }
  return data
}

// Helper to fetch general website data content
export async function getWebsiteData(section_key) {
  const { data, error } = await supabase
    .from('website_data')
    .select('content')
    .eq('section_key', section_key)
    .single()
    
  if (error) {
    if (error.code !== 'PGRST116') { // PGRST116 is "No rows found"
      console.error(`Error fetching website data for ${section_key}:`, error)
    }
    return null;
  }
  return data?.content;
}
