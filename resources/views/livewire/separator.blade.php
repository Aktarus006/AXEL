<div class="w-full bg-black py-8 relative overflow-hidden group" x-data="{ hovering: false }">
    <!-- Animated Objects Container -->
    <div class="flex items-center justify-between px-16 relative">
        <!-- Left Side Objects -->
        <div class="flex items-center space-x-4">
            <div class="w-8 h-8 border-4 border-white group-hover:border-red-600 transition-all duration-300 transform group-hover:rotate-45"></div>
            <div class="w-8 h-8 bg-white group-hover:bg-red-600 transition-all duration-500 transform group-hover:translate-y-2"></div>
            <div class="w-8 h-8 border-4 border-white group-hover:border-red-600 transition-all duration-700 transform group-hover:-rotate-90"></div>
        </div>

        <!-- Center Objects -->
        <div class="flex items-center space-x-6">
            <div class="w-12 h-1 bg-white group-hover:bg-red-600 transition-all duration-300 transform group-hover:scale-x-150"></div>
            <div class="w-12 h-12 border-4 border-white group-hover:border-red-600 transition-all duration-500 transform group-hover:rotate-180 flex items-center justify-center">
                <div class="w-6 h-6 border-4 border-white group-hover:border-red-600 transition-all duration-700 transform group-hover:-rotate-45"></div>
            </div>
            <div class="w-12 h-1 bg-white group-hover:bg-red-600 transition-all duration-300 transform group-hover:scale-x-150"></div>
        </div>

        <!-- Right Side Objects -->
        <div class="flex items-center space-x-4">
            <div class="w-8 h-8 border-4 border-white group-hover:border-red-600 transition-all duration-700 transform group-hover:rotate-90"></div>
            <div class="w-8 h-8 bg-white group-hover:bg-red-600 transition-all duration-500 transform group-hover:-translate-y-2"></div>
            <div class="w-8 h-8 border-4 border-white group-hover:border-red-600 transition-all duration-300 transform group-hover:-rotate-45"></div>
        </div>
    </div>

    <!-- Horizontal Lines -->
    <div class="absolute top-0 left-0 right-0 h-1 bg-white group-hover:bg-red-600 transition-colors duration-300"></div>
    <div class="absolute bottom-0 left-0 right-0 h-1 bg-white group-hover:bg-red-600 transition-colors duration-300"></div>
</div>
