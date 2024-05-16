<?php

namespace App\Livewire;

use App\Models\Link;
use App\Models\Search;
use Livewire\Component;
use App\Models\Framework;
use Illuminate\Support\Arr;
use Livewire\Attributes\Computed;
use Illuminate\Support\Facades\Log;

class LaraSearch extends Component
{

    public $search_history = [];
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
            
            // Initialize an empty array to store the query results
            $results = [];
    
            // Iterate over each keyword and perform a separate query for each
            foreach ($keywords as $keyword) {
                $query = Link::with('framework')
                    ->where(function ($query) use ($keyword) {
                        $query->where('topic_title', 'LIKE', "%$keyword%")
                              ->orWhere('page_title', 'LIKE', "%$keyword%")
                              ->orWhere('section_title', 'LIKE', "%$keyword%")
                              ->orWhere('link_title', 'LIKE', "%$keyword%");
                    })
                    ->orderBy('framework_id')
                    ->get();
    
                // Merge the results of the current query with the overall results
                $results = array_merge($results, $query->toArray());
            }
    
            // Group the merged results by link id and count the occurrences of each link id
            $occurrences = array_count_values(array_column($results, 'id'));
    
            // Filter the merged results to include only those where any keywords were found
            $this->results = collect($results)->filter(function($link) use ($keywords, $occurrences) {
                $found = false;
    
                foreach ($keywords as $keyword) {
                    if (stripos($link['topic_title'], $keyword) !== false
                        || stripos($link['page_title'], $keyword) !== false
                        || stripos($link['section_title'], $keyword) !== false
                        || stripos($link['link_title'], $keyword) !== false
                    ) {
                        $found = true;
                        break;
                    }
                }
    
                // Check if any keywords were found for this link
                return $found && in_array($link['framework_id'], $this->filters);
            });
    
            // Remove duplicate entries from the results
            $this->results = $this->results->unique('id');
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
