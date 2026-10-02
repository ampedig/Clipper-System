<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\RejectionTemplate;
use Illuminate\Http\Request;

class RejectionTemplateController extends Controller
{
    public function index()
    {
        $templates = RejectionTemplate::latest()->paginate(10);
        return view('dashboard.rejection-templates.index', compact('templates'));
    }

    public function create()
    {
        return view('dashboard.rejection-templates.create');
    }

    public function store(Request $request)
    {
        // Auto-format command: slugify and prepend '/'
        $formattedCommand = '/' . \Illuminate\Support\Str::slug($request->command);
        $request->merge(['command' => $formattedCommand]);

        $request->validate([
            'title' => ['required', 'string', 'max:100'],
            'command' => ['required', 'string', 'max:50', 'unique:rejection_templates,command'],
            'message' => ['required', 'string', 'max:1000'],
        ]);

        RejectionTemplate::create($request->all());

        return redirect()->route('admin.rejection-templates.index')->with('success', 'Template penolakan berhasil ditambahkan!');
    }

    public function edit(RejectionTemplate $rejectionTemplate)
    {
        return view('dashboard.rejection-templates.edit', compact('rejectionTemplate'));
    }

    public function update(Request $request, RejectionTemplate $rejectionTemplate)
    {
        // Auto-format command: slugify and prepend '/'
        $formattedCommand = '/' . \Illuminate\Support\Str::slug($request->command);
        $request->merge(['command' => $formattedCommand]);

        $request->validate([
            'title' => ['required', 'string', 'max:100'],
            'command' => ['required', 'string', 'max:50', 'unique:rejection_templates,command,' . $rejectionTemplate->id],
            'message' => ['required', 'string', 'max:1000'],
        ]);

        $rejectionTemplate->update($request->all());

        return redirect()->route('admin.rejection-templates.index')->with('success', 'Template penolakan berhasil diperbarui!');
    }

    public function destroy(RejectionTemplate $rejectionTemplate)
    {
        $rejectionTemplate->delete();

        return redirect()->route('admin.rejection-templates.index')->with('success', 'Template penolakan berhasil dihapus!');
    }
}
