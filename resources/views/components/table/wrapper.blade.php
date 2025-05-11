<div class="overflow-x-auto bg-white rounded-lg dark:bg-gray-800 shadow-lg">
    <table class="min-w-full divide-y divide-gray-200 dark:divide-gray-700">
        <thead class="bg-gray-50 dark:bg-gray-700">
            {{ $thead }}
        </thead>
        <tbody class="divide-y divide-gray-200 dark:divide-gray-700 bg-white dark:bg-gray-800">
            {{ $slot }}
        </tbody>
    </table>
</div>