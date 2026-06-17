<span class="inline-flex px-2.5 py-0.5 rounded-full text-xs font-medium {{ $priority === 'low' ? 'bg-blue-100 dark:bg-blue-900/30 text-blue-700 dark:text-blue-400' : ($priority === 'medium' ? 'bg-yellow-100 dark:bg-yellow-900/30 text-yellow-700 dark:text-yellow-400' : 'bg-red-100 dark:bg-red-900/30 text-red-700 dark:text-red-400') }}">
    {{ $priority === 'low' ? 'منخفضة' : ($priority === 'medium' ? 'متوسطة' : 'عالية') }}
</span>
