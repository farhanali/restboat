<!-- History/Collections listing chooser and delete all button -->
<div class="toolbar">
    <p class="toolbar-text">{{ $entries }} Entries</p>

    <div class="btn-toolbar">
        <div class="btn-group">

            <div class="btn-group">
                @if(Request::is('collections*'))
                    <button type="button" class="btn btn-plain dropdown-toggle" data-toggle="dropdown">
                        <i class="fa fa-folder-o"> Collections</i>
                    </button>
                @elseif(Request::is('history*'))
                    <button type="button" class="btn btn-plain dropdown-toggle" data-toggle="dropdown">
                        <i class="fa fa-clock-o"> History</i>
                    </button>
                @endif
                <ul class="dropdown-menu">
                    <li>
                        <a href="{{ route('history.index') }}">
                            <i class="fa fa-clock-o"> History</i>
                        </a>
                    </li>
                    <li>
                        <a href="{{ route('collections.index') }}">
                            <i class="fa fa-folder-o"> Collections</i>
                        </a>
                    </li>
                </ul>
            </div>
            <!-- Add/send request -->
            <button type="button" class="btn btn-plain" data-toggle="modal" data-target="{{ Request::is('collections*') ? '#add-request' : '#send-request' }}">
                <a href="#"><i class="fa fa-plus"></i></a>
            </button>
        </div>

        <!-- remove all entries / requests -->
        <div class="btn-group">
            <button type="button" class="btn btn-plain danger" data-toggle="modal" data-target="#remove-all-entries">
                <a href="#"><i class="fa fa-trash-o"></i></a>
            </button>
        </div>
    </div>
</div>