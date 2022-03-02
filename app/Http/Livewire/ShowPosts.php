<?php

namespace App\Http\Livewire;

use App\Models\Post;
use Illuminate\Support\Facades\Storage;
use Livewire\Component;
use Livewire\WithFileUploads;
use Livewire\WithPagination;

class ShowPosts extends Component
{
    use WithFileUploads;
    use WithPagination;
    public $search = '';
    public $post, $open_edit = false;
    public $sort = 'Id';
    public $direction = 'desc';
    public $image;
    public $identificador;
    public $can = '10';
    public $readyToLoad = false;
    protected $queryString = [
        'can' => ['except' => '10'],
        'sort' => ['except' => 'Id'],
        'direction' => ['except' => 'desc'],
        'search' => ['except' => ''],
    ];

    protected $listeners = ['render','delete'];
    public function mount()
    {
        $this->identificador = rand();
        $this->post = new Post();

    }



    protected $rules = [
        'post.title' => 'required',
        'post.content' => 'required',
    ];

    public function loadPost(){
        $this->readyToLoad = true;
    }

    public function render()
    {

        if ($this->readyToLoad) {
            $posts = Post::where('title', 'like', '%' . $this->search . '%')
                ->orwhere('content', 'like', '%' . $this->search . '%')
                ->orderby($this->sort, $this->direction)
                ->paginate($this->can);
        } else {
            $posts = [];
        }

        return view('livewire.show-posts', compact('posts'));
    }

    public function order($sort)
    {
        if ($this->sort == $sort) {
            if ($this->direction == 'desc') {
                $this->direction = 'asc';
            } else {
                $this->direction = 'desc';
            }
        } else {
            $this->sort = $sort;
            $this->direction = 'asc';
        }

    }

    public function edit(Post $post)
    {
        $this->post = $post;
        $this->open_edit = true;
    }

    public function updatingSearch()
    {
        $this->resetPage();
    }

    public function update()
    {

        $this->validate();

        if ($this->image) {
            Storage::delete([$this->post->image]);
            $this->post->image = $this->image->store('public/posts');
        }

        $this->post->save();
        $this->reset(['open_edit', 'image']);
        $this->identificador = rand();
        $this->emit('alert', 'El post se actualizo satisfactoriamente');
    }

    public function delete(Post $post){
        $post->delete();
    }
}
