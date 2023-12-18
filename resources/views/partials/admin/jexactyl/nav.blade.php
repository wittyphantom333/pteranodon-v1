@section('pteranodon::nav')
    <div class="row">
        <div class="col-xs-12">
            <div class="nav-tabs-custom">
                <ul class="nav nav-tabs">

                    <li @if($activeTab === 'index') class="active "@endif>
                        <a href="{{ route('admin.index') }}">Home</a>
                    </li>
                    <li @if($activeTab === 'appearance') class="active" @endif>
                        <a href="{{ route('admin.pteranodon.appearance') }}">Appearance</a>
                    </li>
                    <li @if($activeTab === 'mail') class="active" @endif>
                        <a href="{{ route('admin.pteranodon.mail') }}">Mail</a>
                    </li>
                    <li @if($activeTab === 'advanced') class="active" @endif>
                        <a href="{{ route('admin.pteranodon.advanced') }}">Advanced</a>
                    </li>

                    <li style="margin-left: 5px; margin-right: 5px;"><a>-</a></li>

                    <li @if($activeTab === 'store') class="active" @endif>
                        <a href="{{ route('admin.pteranodon.store') }}">Storefront</a>
                    </li>
                    <li @if($activeTab === 'registration') class="active" @endif>
                        <a href="{{ route('admin.pteranodon.registration') }}">Registration</a>
                    </li>
                    <li @if($activeTab === 'approvals') class="active" @endif>
                        <a href="{{ route('admin.pteranodon.approvals') }}">Approvals</a>
                    </li>
                    <li @if($activeTab === 'server') class="active" @endif>
                        <a href="{{ route('admin.pteranodon.server') }}">Server Settings</a>
                    </li>
                    <li @if($activeTab === 'referrals') class="active" @endif>
                        <a href="{{ route('admin.pteranodon.referrals') }}">Referrals</a>
                    </li>
                    <li @if($activeTab === 'alerts') class="active" @endif>
                        <a href="{{ route('admin.pteranodon.alerts') }}">Alerts</a>
                    </li>
                    <li @if($activeTab === 'coupons') class="active" @endif>
                        <a href="{{ route('admin.pteranodon.coupons') }}">Coupons</a>
                    </li>
                </ul>
            </div>
        </div>
    </div>
@endsection
