<div class="relative w-full py-8 overflow-hidden bg-black group" x-data="{ hovering: false }">
    <!-- Animated Objects Container -->
    <div class="relative flex items-center justify-between px-16">
        <!-- Left Side Objects -->
        <div class="flex items-center space-x-4">
            <div class="w-8 h-8 transition-all duration-300 transform border-4 border-white group-hover:border-red-700 group-hover:rotate-45"></div>
            <div class="w-8 h-8 transition-all duration-500 transform bg-white group-hover:bg-red-700 group-hover:translate-y-2"></div>
            <div class="w-8 h-8 transition-all duration-700 transform border-4 border-white group-hover:border-red-700 group-hover:-rotate-90"></div>
        </div>

        <!-- Center Objects -->
        <div class="flex items-center space-x-6">
            <div class="w-12 h-1 transition-all duration-300 transform bg-white group-hover:bg-red-700 group-hover:scale-x-150"></div>
            <div class="flex items-center justify-center w-12 h-12 transition-all duration-500 transform border-4 border-white group-hover:border-red-700 group-hover:rotate-180">
                <div class="flex items-center justify-center w-6 h-6 text-center text-white transition-all duration-700 transform group-hover:-rotate-45"></div>
            </div>
            <div class="w-12 h-1 transition-all duration-300 transform bg-white group-hover:bg-red-700 group-hover:scale-x-150"></div>
        </div>

        <!-- Right Side Objects -->
        <div class="flex items-center space-x-4">
            <div class="w-8 h-8 transition-all duration-700 transform border-4 border-white group-hover:border-red-700 group-hover:rotate-90"></div>
            <div class="w-8 h-8 transition-all duration-500 transform bg-white group-hover:bg-red-700 group-hover:-translate-y-2"></div>
            <div class="w-8 h-8 transition-all duration-300 transform border-4 border-white group-hover:border-red-700 group-hover:-rotate-45"></div>
        </div>
    </div>

    <!-- Horizontal Lines -->
    <div class="absolute top-0 left-0 right-0 h-1 transition-colors duration-300 bg-white group-hover:bg-red-700"></div>
    <div class="absolute bottom-0 left-0 right-0 h-1 transition-colors duration-300 bg-white group-hover:bg-red-700"></div>
</div>
