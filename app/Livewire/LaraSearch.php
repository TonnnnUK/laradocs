<?php

namespace App\Livewire;

use App\Models\Link;
use App\Models\Search;
use Livewire\Component;
use App\Models\Framework;
use Illuminate\Support\Arr;
use Livewire\Attributes\Computed;
use Illuminate\Support\Facades\Log;
use Illuminate\Foundation\Auth\User;
use Illuminate\Support\Facades\Auth;

class LaraSearch extends Component
{

    public $search_history = [];
    public $link_history = [];
    public $search;
    public $frameworks;
    public $allFilters = true;
    public $allFilterIds;
    public $filters = [];
    public $hasSearched = false;
    public $results = [];

    public function render()
    {
        return view('livewire.lara-search');
    }

    public function mount(){
        $this->frameworks = Framework::all();
        $this->filters = $this->frameworks->pluck('id')->toArray();
        $this->allFilterIds = $this->filters;

        if( Auth::user() ){
            Auth::user()->load(['history' => function ($query) {
                $query->limit(10);
            }]);
            $this->link_history = Auth::user()->history;
        }
    }

    public function updatedSearch(){

        // Validate the search input
        $this->validate([
            'search' => 'nullable|string|min:3', // Adjust validation rules as needed
        ]);

       // Check if search is not empty and contains at least 3 characters
       if ($this->search) {
            $this->searchDocs($this->search);
        } else {
            // Reset results if search is empty
            $this->resetSearch();
        }
    }

    
    public function updatedFilters(){
        if( $this->filters == $this->allFilterIds){
            $this->allFilters = true;
        } else {
            $this->allFilters = false;
        }
    }

    public function searchDocs($find)
    {
        if (strlen($find) >= 3) {
            $this->hasSearched = true;
            
            // Split the search string into individual words
            $keywords = explode(" ", $find);
            
            // Initialize an empty collection to store the query results
            $results = collect();
        
            // Perform a query for each priority field
            $priorityFields = [
                'frameworks.name' => 'framework_id',
                'topic_title' => 'topic_title',
                'page_title' => 'page_title',
                'section_title' => 'section_title'
            ];
            
            foreach ($priorityFields as $field => $relation) {
                foreach ($keywords as $keyword) {
                    $query = Link::where(function ($query) use ($field, $keyword, $relation) {
                            if ($relation === 'framework_id') {
                                $query->whereHas('framework', function ($query) use ($keyword) {
                                    $query->where('name', 'LIKE', "%$keyword%");
                                });
                            } else {
                                $query->where($field, 'LIKE', "%$keyword%");
                            }
                        })
                        ->whereIn('framework_id', $this->filters)
                        ->orderBy('framework_id')
                        ->get();
                    
                    // Merge the results of the current query with the overall results
                    $results = $results->merge($query);
                }
            }
            
            if (!in_array($this->search, $this->search_history)) {
                $search = Search::firstOrCreate(['search' => $this->search], ['count' => 0]);
                $search->increment('count');
                $this->search_history[] = $this->search;
            }
            
            // Count the number of keyword matches for each link
            $this->results = $results->map(function ($link) use ($keywords) {
                $link->keyword_matches = 0;
                $link->topic_title_matches = 0;
                $link->page_title_matches = 0;
                
                foreach ($keywords as $keyword) {
                    if (stripos($link->framework->name ?? '', $keyword) !== false) $link->keyword_matches++;
                    if (stripos($link->topic_title, $keyword) !== false) {
                        $link->keyword_matches++;
                        $link->topic_title_matches++;
                    }
                    if (stripos($link->page_title, $keyword) !== false) {
                        $link->keyword_matches++;
                        $link->page_title_matches++;
                    }
                    if (stripos($link->section_title, $keyword) !== false) $link->keyword_matches++;
                    if (stripos($link->link_title, $keyword) !== false) $link->keyword_matches++;
                }
                
                return $link;
            });
            
            // Sort results by priority and then by the number of keyword matches
            $this->results = $results->sortByDesc(function ($link) {
                return [
                    $link->topic_title_matches, 
                    $link->page_title_matches
                ];
            })->unique('id')->values();
        }
    }

    public function filterSearch(){
        $this->searchDocs($this->search);
    }

    public function resetSearch()
    {
        // Reset search results and flags
        $this->hasSearched = false;
        $this->results = [];
    }

    #[Computed]
    public function filtersToInt()
    {
        $values = [];
        foreach($this->filters as $item){
            $values[] = intval($item);
        }
        return $values;
    }

    public function toggleAll(){

        if( $this->filtersToInt() != $this->allFilterIds){
            $this->filters = $this->allFilterIds;
        } else {
            $this->filters = [];
        }

        $this->searchDocs($this->search);
    }

}
