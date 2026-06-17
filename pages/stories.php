<!doctype html>
<html lang="en">
    <head>
        <?php include 'includes/head.php'; ?>
        <script src="assets/js/stories.js" defer></script>
    </head>

    <body class="font-nunito flex flex-col items-center">
        <div class="w-full overflow-clip 2xl:max-w-[1536px] min-h-screen">
            <?php include 'includes/navbar.php'; ?>

            <!-- Header Section -->
            <section class="bg-white py-12 px-4 shadow-sm">
                <div class="max-w-4xl mx-auto text-center">
                    <h1 class="font-poppins text-4xl font-bold text-neutral-800 mb-4">Success Stories</h1>
                    <p class="text-lg text-neutral-600 mb-8">Wholesome updates from our adopted pets in their forever homes.</p>
                    
                    <?php if(isset($_SESSION['id'])): ?>
                        <button onclick="openModal()" class="bg-green-500 hover:bg-green-600 text-white font-bold py-3 px-8 rounded-full transition-all shadow-lg transform hover:scale-105">
                            Share Your Story
                        </button>
                    <?php else: ?>
                        <div class="bg-blue-50 border-l-4 border-blue-500 p-4 inline-block">
                            <p class="text-blue-700">
                                <a href="login" class="font-bold underline">Login</a> to share your own success story!
                            </p>
                        </div>
                    <?php endif; ?>
                </div>
            </section>

            <!-- Stories Feed -->
            <main class="max-w-6xl mx-auto py-12 px-4">
                <div id="stories-container" class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 gap-8">
                    <!-- Stories will be loaded here via JS -->
                    <div class="col-span-full text-center py-20">
                        <div class="inline-block animate-spin rounded-full h-8 w-8 border-t-2 border-b-2 border-green-500"></div>
                        <p class="mt-4 text-neutral-500">Loading stories...</p>
                    </div>
                </div>
            </main>

            <!-- Submission Modal -->
            <div id="storyModal" class="fixed inset-0 bg-black/60 backdrop-blur-sm z-50 hidden items-center justify-center p-4">
                <div class="bg-white rounded-2xl shadow-2xl max-w-lg w-full overflow-hidden transform transition-all">
                    <div class="p-6 border-b border-neutral-100 flex justify-between items-center">
                        <h2 class="text-2xl font-bold font-poppins text-neutral-800">Share Your Success Story</h2>
                        <button onclick="closeModal()" class="text-neutral-400 hover:text-neutral-600">
                            <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12"></path></svg>
                        </button>
                    </div>
                    
                    <form id="storyForm" class="p-6 space-y-4">
                        <div>
                            <label class="block text-sm font-semibold text-neutral-700 mb-1">Pet's Name</label>
                            <input type="text" name="pet_name" required class="w-full px-4 py-2 border border-neutral-300 rounded-lg focus:ring-2 focus:ring-green-500 focus:border-green-500 outline-none transition-all" placeholder="e.g. Luna">
                        </div>
                        
                        <div>
                            <label class="block text-sm font-semibold text-neutral-700 mb-1">Your Story</label>
                            <textarea name="story_text" required rows="4" class="w-full px-4 py-2 border border-neutral-300 rounded-lg focus:ring-2 focus:ring-green-500 focus:border-green-500 outline-none transition-all" placeholder="How is your pet doing in their new home?"></textarea>
                        </div>
                        
                        <div>
                            <label class="block text-sm font-semibold text-neutral-700 mb-1">Pet Photo</label>
                            <div class="mt-1 flex justify-center px-6 pt-5 pb-6 border-2 border-neutral-300 border-dashed rounded-lg hover:border-green-500 transition-colors cursor-pointer relative">
                                <div class="space-y-1 text-center">
                                    <svg class="mx-auto h-12 w-12 text-neutral-400" stroke="currentColor" fill="none" viewBox="0 0 48 48">
                                        <path d="M28 8H12a4 4 0 00-4 4v20m32-12v8m0 0v8a4 4 0 01-4 4H12a4 4 0 01-4-4v-4m32-4l-3.172-3.172a4 4 0 00-5.656 0L28 28M8 32l9.172-9.172a4 4 0 015.656 0L28 28m0 0l4 4m4-24h8m-4-4v8m-12 4h.02" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" />
                                    </svg>
                                    <div class="flex text-sm text-neutral-600">
                                        <label class="relative cursor-pointer bg-white rounded-md font-medium text-green-600 hover:text-green-500 focus-within:outline-none">
                                            <span>Upload a file</span>
                                            <input id="file-upload" name="story_image" type="file" accept="image/*" class="sr-only" required onchange="updateFileName(this)">
                                        </label>
                                        <p class="pl-1">or drag and drop</p>
                                    </div>
                                    <p class="text-xs text-neutral-500">PNG, JPG, WEBP up to 5MB</p>
                                    <p id="file-name" class="text-sm font-semibold text-green-600 mt-2"></p>
                                </div>
                            </div>
                        </div>
                        
                        <div class="pt-4">
                            <button type="submit" class="w-full bg-green-500 hover:bg-green-600 text-white font-bold py-3 rounded-lg transition-all shadow-md">
                                Post Story
                            </button>
                        </div>
                    </form>
                </div>
            </div>
        </div>
    </body>
</html>
