<?php

namespace App\Controllers\Admin;

use App\Controllers\BaseController;
use App\Models\SlideModel;

class SlideController extends BaseController
{
    protected $slideModel;

    public function __construct()
    {
        helper('permission');
        $this->slideModel = new SlideModel();
    }

    public function index()
    {
        if ($redirect = checkPermissionOrRedirect('settings', 'view')) {
            return $redirect;
        }

        $data = [
            'title' => 'Gestion du Slider',
            'slides' => $this->slideModel->orderBy('order', 'ASC')->findAll()
        ];

        return view('admin/slides/index', $data);
    }

    public function create()
    {
        if ($redirect = checkPermissionOrRedirect('settings', 'edit')) {
            return $redirect;
        }

        $data = [
            'title' => 'Nouvelle Slide'
        ];

        return view('admin/slides/form', $data);
    }

    public function edit($id)
    {
        if ($redirect = checkPermissionOrRedirect('settings', 'edit')) {
            return $redirect;
        }

        $slide = $this->slideModel->find($id);

        if (!$slide) {
            return redirect()->to(base_url('admin/slides'))->with('error', 'Slide introuvable');
        }

        $data = [
            'title' => 'Modifier la Slide',
            'slide' => $slide
        ];

        return view('admin/slides/form', $data);
    }

    public function save($id = null)
    {
        if ($redirect = checkPermissionOrRedirect('settings', 'edit')) {
            return $redirect;
        }

        $rules = [
            'title' => 'required|min_length[3]',
            'order' => 'permit_empty|integer'
        ];

        if (!$this->validate($rules)) {
            return redirect()->back()->withInput()->with('errors', $this->validator->getErrors());
        }

        $data = [
            'title' => $this->request->getPost('title'),
            'subtitle' => $this->request->getPost('subtitle'),
            'description' => $this->request->getPost('description'),
            'image' => $this->request->getPost('image'),
            'button_text' => $this->request->getPost('button_text'),
            'button_link' => $this->request->getPost('button_link'),
            'button_style' => $this->request->getPost('button_style'),
            'text_color' => $this->request->getPost('text_color'),
            'overlay_opacity' => $this->request->getPost('overlay_opacity'),
            'order' => $this->request->getPost('order') ?: 0,
            'active' => $this->request->getPost('active') ? 1 : 0
        ];

        if ($id) {
            $this->slideModel->update($id, $data);
            $message = 'Slide modifiée avec succès';
        } else {
            $this->slideModel->insert($data);
            $message = 'Slide créée avec succès';
        }

        return redirect()->to(base_url('admin/slides'))->with('success', $message);
    }

    public function delete($id)
    {
        if ($redirect = checkPermissionOrRedirect('settings', 'delete')) {
            return $redirect;
        }

        $this->slideModel->delete($id);

        return redirect()->to(base_url('admin/slides'))->with('success', 'Slide supprimée avec succès');
    }

    public function updateOrder()
    {
        if ($redirect = checkPermissionOrRedirect('settings', 'edit')) {
            return $redirect;
        }

        $order = $this->request->getJSON(true);

        foreach ($order as $index => $id) {
            $this->slideModel->update($id, ['order' => $index + 1]);
        }

        return $this->response->setJSON(['status' => 'success']);
    }
}
