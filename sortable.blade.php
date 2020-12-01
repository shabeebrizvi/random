@extends('layouts.dashboard')

@section('content')
<div class="content">
    <section class="mt-3">
        <div class="container-fluid">
            <div class="row d-flex justify-content-between">
                <div class="col-auto">
                    <h4 class="elements__title">Pages</h4>
                </div>
                <div class="col-auto">
                    <a href="{{ route('pages.create') }}" class="btn btn-outline-danger"><i class="fad fa-plus-circle mr-1"></i>Create</a>
                </div>
            </div>
            <div class="row">
                <div class="col">
                    <table class="table table-striped">
                        <thead>
                            <tr>
                                <th scope="col">Name</th>
                                <th scope="col">Slug</th>
                                <th scope="col">Last Updated At</th>
                                <th scope="col">Action</th>
                            </tr>
                        </thead>
                        <tbody class="sort_menu">
                            @foreach(App\Models\Page::orderBy('order', 'ASC')->get() as $page)
                            <tr data-id="{{ $page->id }}">
                                <td class="handle" scope="row"><a href="/{{ $page->slug }}" class="text-info">{{ $page->name }}<i class="fad fa-external-link pl-2"></i></a></td>
                                <td class="handle">{{ $page->slug }}</td>
                                <td class="handle">{{ $page->updated_at->format('M j, Y h:m:s A') }}</td>
                                <td><a href="pages/{{ $page->slug }}/edit"><i class="fad fa-edit text-success"></i></a></td>
                            </tr>
                            @endforeach
                        </tbody>
                    </table>
                </div>
            </div>
        </div>
    </section>
</div>
@endsection
@push('styles')
<style>
    .highlight {
        background: #f7e7d3;
        min-height: 30px;
        list-style-type: none;
    }

    .handle {
        min-width: 18px;
        cursor: move;
    }
</style>
@endpush
@push('scripts')
<script src="https://cdnjs.cloudflare.com/ajax/libs/jqueryui/1.11.4/jquery-ui.min.js"></script>
<script>
    $(document).ready(function() {
        var target = $('.sort_menu');
        target.sortable({
            placeholder: 'highlight',
            axis: "y",
            update: function(e, tbody) {
                var sortData = target.sortable('toArray', {
                    attribute: 'data-id'
                })
                updateToDatabase(sortData.join(','))
            }
        })

        function updateToDatabase(idString) {
            $.ajaxSetup({
                headers: {
                    'X-CSRF-TOKEN': '{{ csrf_token() }}'
                }
            });

            $.ajax({
                url: '{{ url('page-reorder') }}',
                method: 'POST',
                data: {
                    ids: idString
                },
                success: function(response) {
                    console.log(response);
                },
                error: function(data, textStatus, errorThrown) {
                    console.log(data);
                },
            })
        }

    })
</script>
@endpush
