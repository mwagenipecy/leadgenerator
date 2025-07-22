<x-app-layout>
    



 

@if(auth()->user()->role=='lender')
 <livewire:lender.lender-dashboard />


 @elseif(auth()->user()->role=='borrower')




  
<livewire:borrower.borrower-dashboard />

@elseif(auth()->user()->role=='super_admin')

<livewire:admin.admin-dashboard />


@endif 


</x-app-layout>
