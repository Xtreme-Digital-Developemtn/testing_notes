<span class="inline-flex px-2.5 py-0.5 rounded-full text-xs font-medium {{ $status === 'not_started' ? 'bg-gray-100 dark:bg-gray-800 text-gray-600 dark:text-gray-400' : ($status === 'in_progress' ? 'bg-yellow-100 dark:bg-yellow-900/30 text-yellow-700 dark:text-yellow-400' : 'bg-green-100 dark:bg-green-900/30 text-green-700 dark:text-green-400') }}">
    {{ $status === 'not_started' ? 'لم تبدأ' : ($status === 'in_progress' ? 'قيد التنفيذ' : 'تم الحل') }}
</span>
