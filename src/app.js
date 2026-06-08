import { getBlogPosts, getWebsiteData } from './lib/supabase.js';

document.addEventListener('DOMContentLoaded', async () => {
    // Attempt to load dynamic data from Supabase
    
    // 1. Fetch Dynamic Website Stats Data
    const statsData = await getWebsiteData('stats');
    if (statsData) {
        // Find stats container and update dynamic counters if they exist
        const statCounters = document.querySelectorAll('.stat-item .counter');
        if (statCounters.length > 0) {
            statCounters[0].setAttribute('data-target', statsData.livesImpacted || 5000);
            statCounters[1].setAttribute('data-target', statsData.bloodDonors || 100);
            statCounters[2].setAttribute('data-target', statsData.initiatives || 50);
            statCounters[3].setAttribute('data-target', statsData.volunteers || 100);
        }
    }

    // 2. Fetch Blog Posts and Render them
    const blogPosts = await getBlogPosts();
    renderBlogPosts(blogPosts);
});

function renderBlogPosts(posts) {
    // If no posts fetched, we might not have the table set up yet.
    if (!posts || posts.length === 0) return;

    // Check if the blog section exists, if not, inject it before the footer
    let blogSection = document.getElementById('blog-section');
    if (!blogSection) {
        blogSection = document.createElement('section');
        blogSection.id = 'blog-section';
        blogSection.style.backgroundColor = 'var(--bg-shade-2)';
        
        const container = document.createElement('div');
        container.className = 'container scroll-reveal visible';
        
        const title = document.createElement('h2');
        title.className = 'section-title';
        title.textContent = 'Latest Updates & Stories';
        
        const grid = document.createElement('div');
        grid.className = 'blog-grid';
        grid.id = 'blog-grid-container';
        grid.style.display = 'grid';
        grid.style.gridTemplateColumns = 'repeat(auto-fit, minmax(300px, 1fr))';
        grid.style.gap = '30px';
        
        container.appendChild(title);
        container.appendChild(grid);
        blogSection.appendChild(container);
        
        const mainElements = document.querySelectorAll('section');
        const lastSection = mainElements[mainElements.length - 1];
        lastSection.parentNode.insertBefore(blogSection, lastSection.nextSibling);
    }

    const gridContainer = document.getElementById('blog-grid-container');
    gridContainer.innerHTML = ''; // clear existing

    posts.forEach(post => {
        const d = new Date(post.published_at);
        const card = document.createElement('div');
        card.className = 'blog-card';
        card.style.backgroundColor = 'var(--bg-light)';
        card.style.borderRadius = '12px';
        card.style.overflow = 'hidden';
        card.style.boxShadow = '0 10px 30px rgba(0, 0, 0, 0.2)';
        
        card.innerHTML = `
            ${post.featured_image ? `<img src="${post.featured_image}" alt="${post.title}" style="width: 100%; height: 200px; object-fit: cover;">` : '<div style="width: 100%; height: 200px; background: var(--bg-shade-4);"></div>'}
            <div style="padding: 20px;">
                <div style="color: var(--secondary); font-size: 0.85rem; margin-bottom: 10px; text-transform: uppercase;">
                    ${post.category?.name || 'Uncategorized'} • ${d.toLocaleDateString()}
                </div>
                <h3 style="color: var(--primary); font-size: 1.25rem; margin-bottom: 15px;">${post.title}</h3>
                <p style="color: var(--text-light); font-size: 0.95rem; margin-bottom: 20px;">
                    ${post.excerpt || post.content.substring(0, 100) + '...'}
                </p>
                <div style="display: flex; align-items: center; gap: 10px; border-top: 1px solid rgba(255,255,255,0.1); padding-top: 15px;">
                    ${post.author?.avatar_url ? `<img src="${post.author.avatar_url}" style="width: 30px; height: 30px; border-radius: 50%;">` : ''}
                    <span style="color: var(--cream); font-size: 0.9rem;">${post.author?.name || 'Admin'}</span>
                </div>
            </div>
        `;
        gridContainer.appendChild(card);
    });
}
