document.addEventListener('DOMContentLoaded', () => {
    loadStories();

    const storyForm = document.getElementById('storyForm');
    if (storyForm) {
        storyForm.addEventListener('submit', submitStory);
    }
});

function openModal() {
    document.getElementById('storyModal').classList.remove('hidden');
    document.getElementById('storyModal').classList.add('flex');
    document.body.style.overflow = 'hidden';
}

function closeModal() {
    document.getElementById('storyModal').classList.add('hidden');
    document.getElementById('storyModal').classList.remove('flex');
    document.body.style.overflow = 'auto';
}

function updateFileName(input) {
    const fileName = input.files[0] ? input.files[0].name : '';
    document.getElementById('file-name').textContent = fileName;
}

async function loadStories() {
    const container = document.getElementById('stories-container');
    
    try {
        const response = await fetch('scripts/get_stories.php');
        const stories = await response.json();
        
        if (stories.length === 0) {
            container.innerHTML = `
                <div class="col-span-full text-center py-20">
                    <p class="text-neutral-500 text-lg italic">No stories yet. Be the first to share one!</p>
                </div>
            `;
            return;
        }

        container.innerHTML = stories.map(story => renderStoryCard(story)).join('');
    } catch (error) {
        console.error('Error loading stories:', error);
        container.innerHTML = `<p class="col-span-full text-center text-red-500 py-20">Failed to load stories. Please try again later.</p>`;
    }
}

function renderStoryCard(story) {
    const heartIcon = story.is_liked_by_me 
        ? `<svg class="w-6 h-6 text-red-500 fill-current" viewBox="0 0 24 24" xmlns="http://www.w3.org/2000/svg"><path d="M12 21.35l-1.45-1.32C5.4 15.36 2 12.28 2 8.5 2 5.42 4.42 3 7.5 3c1.74 0 3.41.81 4.5 2.09C13.09 3.81 14.76 3 16.5 3 19.58 3 22 5.42 22 8.5c0 3.78-3.4 6.86-8.55 11.54L12 21.35z"/></svg>`
        : `<svg class="w-6 h-6 text-neutral-400 hover:text-red-500 transition-colors" fill="none" stroke="currentColor" viewBox="0 0 24 24" xmlns="http://www.w3.org/2000/svg"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4.318 6.318a4.5 4.5 0 000 6.364L12 20.364l7.682-7.682a4.5 4.5 0 00-6.364-6.364L12 7.636l-1.318-1.318a4.5 4.5 0 00-6.364 0z"></path></svg>`;

    return `
        <div class="bg-white rounded-xl shadow-md overflow-hidden hover:shadow-xl transition-shadow duration-300">
            <div class="h-64 overflow-hidden">
                <img src="${story.image_path}" alt="${story.pet_name}" class="w-full h-full object-cover transform hover:scale-105 transition-transform duration-500">
            </div>
            <div class="p-6">
                <div class="flex justify-between items-start mb-4">
                    <div>
                        <h3 class="text-2xl font-bold font-poppins text-neutral-800">${story.pet_name}</h3>
                        <p class="text-sm text-neutral-500 italic">Shared by ${story.author_name}</p>
                    </div>
                    <button onclick="toggleLike(${story.id})" id="like-btn-${story.id}" class="flex items-center space-x-1 focus:outline-none">
                        <span class="like-icon">${heartIcon}</span>
                        <span class="text-neutral-600 font-semibold like-count">${story.likes_count}</span>
                    </button>
                </div>
                <p class="text-neutral-600 leading-relaxed mb-4 line-clamp-4">${story.story_text}</p>
                <div class="text-xs text-neutral-400 mt-auto">
                    ${new Date(story.created_at).toLocaleDateString(undefined, { year: 'numeric', month: 'long', day: 'numeric' })}
                </div>
            </div>
        </div>
    `;
}

async function submitStory(e) {
    e.preventDefault();
    const formData = new FormData(e.target);
    const submitBtn = e.target.querySelector('button[type="submit"]');
    
    submitBtn.disabled = true;
    submitBtn.textContent = 'Posting...';

    try {
        const response = await fetch('scripts/create_story.php', {
            method: 'POST',
            body: formData
        });
        const result = await response.json();

        if (result.status === 'success') {
            alert(result.message);
            closeModal();
            e.target.reset();
            document.getElementById('file-name').textContent = '';
            loadStories();
        } else {
            alert(result.message);
        }
    } catch (error) {
        console.error('Error submitting story:', error);
        alert('An error occurred. Please try again.');
    } finally {
        submitBtn.disabled = false;
        submitBtn.textContent = 'Post Story';
    }
}

async function toggleLike(storyId) {
    const btn = document.getElementById(`like-btn-${storyId}`);
    const iconSpan = btn.querySelector('.like-icon');
    const countSpan = btn.querySelector('.like-count');
    
    // Prevent double-clicking
    btn.style.pointerEvents = 'none';

    try {
        const formData = new FormData();
        formData.append('story_id', storyId);

        const response = await fetch('scripts/toggle_like.php', {
            method: 'POST',
            body: formData
        });
        const result = await response.json();

        if (result.status === 'success') {
            countSpan.textContent = result.likes_count;
            if (result.liked) {
                iconSpan.innerHTML = `<svg class="w-6 h-6 text-red-500 fill-current" viewBox="0 0 24 24" xmlns="http://www.w3.org/2000/svg"><path d="M12 21.35l-1.45-1.32C5.4 15.36 2 12.28 2 8.5 2 5.42 4.42 3 7.5 3c1.74 0 3.41.81 4.5 2.09C13.09 3.81 14.76 3 16.5 3 19.58 3 22 5.42 22 8.5c0 3.78-3.4 6.86-8.55 11.54L12 21.35z"/></svg>`;
            } else {
                iconSpan.innerHTML = `<svg class="w-6 h-6 text-neutral-400 hover:text-red-500 transition-colors" fill="none" stroke="currentColor" viewBox="0 0 24 24" xmlns="http://www.w3.org/2000/svg"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4.318 6.318a4.5 4.5 0 000 6.364L12 20.364l7.682-7.682a4.5 4.5 0 00-6.364-6.364L12 7.636l-1.318-1.318a4.5 4.5 0 00-6.364 0z"></path></svg>`;
            }
        } else {
            alert(result.message);
        }
    } catch (error) {
        console.error('Error toggling like:', error);
    } finally {
        btn.style.pointerEvents = 'auto';
    }
}
