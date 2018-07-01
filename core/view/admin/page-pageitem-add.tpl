<div id="pageEditor">
    <h3>Adding page item for page "{title}"</h3>
    <form method="POST" action="/admin/savepageitem/">
        <input type="hidden" value="{title}" name="page-name">
        <div class="row">
            <div class="col-xs-12"><input type="text" class="form-control" name="item" placeholder="pagename (can .html but its nvm in admin mode)" id="addPageInput" required></div>
        </div><br/><br/>
        <ul class="nav nav-tabs">
            <li class="active"><a data-toggle="tab" href="#PageEditBlock" class="btn btn-primary">Content</a></li>
            <li><a data-toggle="tab" href="#PageMetaBlock" class="btn btn-info">Metadata</a></li>
            <li><a data-toggle="tab" href="#PageSettingsBlock" class="btn btn-danger">Engine</a></li>
        </ul>
        <div class="tab-content">
            <div id="PageEditBlock" class="panel-group tab-pane fade in active">
                <div class="panel panel-default">
                    <div class="panel-body">
                        <div class="block_data">
                            <div class="row">
                                <div class="col-sm-4">Tag name</div><div class="col-sm-8">Tag data</div>
                            </div>
                            {content}
                            <div class="row">
                                <div class="col-xs-12"><span class="fui-plus-circle" id="addTag"></span></div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
            <div id="PageMetaBlock" class="tab-pane fade in">
                <div class="input-group tagsinput-primary">
                    <span class="input-group-addon palette palette-nephritis">Metatitle</span>
                    <input type="text" class="tagsinput" name="metatitle" data-role="tagsinput" value="{metatitle}">
                </div>
                <div class="input-group tagsinput-primary">
                    <span class="input-group-addon palette palette-nephritis">Keywords</span>
                    <input type="text" class="tagsinput" name="keywords" data-role="tagsinput" value="{keywords}">
                </div>
                <div class="input-group tagsinput-primary">
                    <span class="input-group-addon palette palette-nephritis">Description</span>
                    <input type="text" class="tagsinput" name="description" data-role="tagsinput" value="{description}">
                </div>
            </div>
            <div id="PageSettingsBlock" class="tab-pane fade in">
                <div class="row">
                    <div class="col-xs-12">
                        <div class="input-group">
                            <span class="input-group-addon palette palette-nephritis">Template</span>
                            <input type="text" class="form-control" name="template" value="{template}" required>
                        </div>
                    </div>
                </div>
            </div>
        </div>
        <div class="row">
            <div class="col-sm-3"><input type="submit" class="btn btn-block btn-success" value="Save"> </div>
        </div>
    </form>
    <br />
    <br />
    <br />
</div>
