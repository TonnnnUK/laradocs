<?php

namespace App\Livewire;

use App\Models\Link;
use App\Models\Search;
use Livewire\Component;
use App\Models\Outbound;
use App\Models\Framework;
use Illuminate\Support\Arr;

class ManageData extends Component
{
    public $frameworks;
    public $selected_framework;
    public $selected_json;
    public $searches;
    public $json_files;
    public $files;
    public $outbounds;
    public $added = [];

    public function render()
    {
        return view('livewire.manage-data');
    }

    public function mount(){

        if( auth()->user()->email != 'a.hutchinson86@gmail.com'){
            abort(403);
        }

        $this->outbounds = Outbound::with('link.framework')->orderBy('count', 'DESC')->limit(50)->get();
        $this->searches = Search::limit('50')->get();
        $this->frameworks = Framework::all();

         // Get the path to the directory where JSON files are stored
        $directory = public_path('docscraper/json');

        // Get an array of file paths in the directory
        $this->json_files = glob($directory . '/*.json');

        foreach ($this->json_files as $key => $file) {
            // Read the contents of the JSON file

            $pathArr = explode('/', $file);
            $last = array_slice($pathArr, -1);
            
            $this->files[$key]['path'] = $file; 
            $this->files[$key]['name'] = $last[0];
        }

    }

    public function import(){
        foreach ($this->json_files as $key => $file) {
            // Read the contents of the JSON file

            if($file == $this->selected_json){

                // To get the JSON contents
                $jsonContent = file_get_contents($file);
        
                // Parse the JSON content
                $data = json_decode($jsonContent, true);


                $combined = [];
                if($this->selected_framework == 5){
                    
                    foreach($data as $function_data){
                        $combined = array_merge($combined, $function_data);
                    }

                    $data = $combined;
                }

                $this->createLinks($data);                
            
            }

            
        }

    }

    public function createLinks($data){
        // Do something with the data
        foreach($data as $item){

            if( isset($item['pageLinks'])){ 
                foreach($item['pageLinks'] as $link){
               
                    $new_link = Link::create([
                        'framework_id' => $this->selected_framework,
                        'url' => $link['url'],
                        'topic_title' => $link['topic'],
                        'page_title' => $link['page_title'],
                        'section_title' => $link['section_title'],
                        'link_title' => $link['link_title'],
                    ]);
                }
            } else {
                $new_link = Link::create([
                    'framework_id' => $this->selected_framework,
                    'url' => $item['url'],
                    'topic_title' => $item['topic'],
                    'page_title' => $item['page_title'],
                    'section_title' => $item['section_title'],
                    'link_title' => $item['link_title'],
                ]);
            }


            $this->added[] = "$new_link->topic_title - $new_link->page_title - $new_link->section_title - $new_link->link_title";
            
        }
    }

    public function deleteLinks($id){
        $deleted = Link::where('framework_id', $id)->delete();
    }
}
