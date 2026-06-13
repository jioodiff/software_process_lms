import os
import glob

def process_file(filepath):
    with open(filepath, 'r') as f:
        content = f.read()

    # Replacements
    replacements = [
        ('class="w-full rounded-xl border-slate-200 focus:ring-indigo-500 focus:border-indigo-500 text-sm"', 
         'class="w-full rounded-xl border border-slate-300 px-4 py-2.5 text-sm focus:ring-2 focus:ring-indigo-500 focus:border-indigo-500 transition-colors bg-white shadow-sm"'),
        ('class="w-full rounded-lg border-rose-200 focus:ring-rose-500 focus:border-rose-500 text-sm mb-3 bg-white"',
         'class="w-full rounded-xl border border-rose-300 px-4 py-2.5 text-sm focus:ring-2 focus:ring-rose-500 focus:border-rose-500 transition-colors bg-white shadow-sm mb-3"'),
        ('class="w-full rounded-xl border-slate-200 focus:ring-rose-500 focus:border-rose-500 text-sm"',
         'class="w-full rounded-xl border border-slate-300 px-4 py-2.5 text-sm focus:ring-2 focus:ring-rose-500 focus:border-rose-500 transition-colors bg-white shadow-sm"'),
        ('block text-sm font-medium text-slate-700 mb-1', 'block text-sm font-semibold text-slate-700 mb-2'),
        ('block text-xs font-medium text-slate-700 mb-1', 'block text-xs font-semibold text-slate-700 mb-1.5'),
        ('block text-xs font-medium text-slate-700 mb-1.5', 'block text-xs font-semibold text-slate-700 mb-1.5'),
        ('block text-xs font-medium text-rose-800 mb-1', 'block text-xs font-semibold text-rose-800 mb-1.5'),
        ('class="w-full text-sm text-slate-500 file:mr-4 file:py-2 file:px-4 file:rounded-full file:border-0 file:text-sm file:font-semibold file:bg-indigo-50 file:text-indigo-700 hover:file:bg-indigo-100"',
         'class="block w-full text-sm text-slate-500 file:mr-4 file:py-2.5 file:px-4 file:rounded-xl file:border-0 file:text-sm file:font-semibold file:bg-indigo-50 file:text-indigo-700 hover:file:bg-indigo-100 transition-all border border-slate-200 rounded-xl bg-white shadow-sm"'),
    ]

    new_content = content
    for old, new in replacements:
        new_content = new_content.replace(old, new)

    if new_content != content:
        with open(filepath, 'w') as f:
            f.write(new_content)
        print(f"Updated: {filepath}")

views_dir = 'resources/views'
for root, _, files in os.walk(views_dir):
    for file in files:
        if file.endswith('.blade.php'):
            process_file(os.path.join(root, file))

