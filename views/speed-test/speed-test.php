<section class="overflow-hidden">
    <div class="card container px-4 py-2 px-md-5 text-center text-lg-start my-2">
        <div class="row gx-lg-5 align-items-center mb-5">
            <h2><?= Yii::t('app', 'Network measures') ?></h2>
            <div class="col-md-6">
                <div class="btn-toolbar">
                    <button class="btn btn-sm btn-primary mr-1" type="button" id="btnLatency" data-measure="latency">
                        <i class="fa-solid fa-clock"></i> <?= Yii::t('app', 'Latency') ?>
                    </button>
                    <button class="btn btn-sm btn-primary mr-1" type="button" id="btnupload" data-measure="upload">
                        <i class="fa-solid fa-upload"></i> <?= Yii::t('app', 'Upload') ?>
                    </button>
                    <button class="btn btn-sm btn-primary mr-1" type="button" id="btnDownload" data-measure="download">
                        <i class="fa-solid fa-download"></i> <?= Yii::t('app', 'Download') ?>
                    </button>
                    <button class="btn btn-sm btn-primary" type="button" data-abort disabled>
                        <i class="fa-solid fa-circle-xmark"></i> <?= Yii::t('app', 'Abort') ?>
                    </button>
                </div>
            </div>
            <div class="table-responsive" style="height: 600px;">
                <output><?= Yii::t('app', 'Click one of the above buttons to start network measures…') ?></output>
            </div>
        </div>
    </div>
</div>
<script type="text/javascript">
  $(function() {

    $('#btnLatency').off('click').on('click', function()
    {
        $('output').html('');
    });

    $('#btnDownload').off('click').on('click', function()
    {
        $('output').html('');
    });

    $('#btnupload').off('click').on('click', function()
    {
        $('output').html('');
    });

    var module; // Current Network.js' module

    /*
     * Tooltips
     */

    $('.btn-group').tooltip();

    /*
     * UI
     */

    var UI = {
        $btnStart: $('[data-measure]'),
        $btnAbort: $('[data-abort]'),
        $output: $('output'),

        start: function() {
            rawModule = $(this).data('measure');
            module = rawModule.charAt(0).toUpperCase() + rawModule.slice(1);

            UI.$btnStart.prop('disabled', true);
            UI.$btnAbort.prop('disabled', false);

            net[rawModule].start();

            // The latency module doesn't have a start event, we must trigger it manually.
            if (rawModule == 'latency') {
                net[rawModule].trigger('start');
            }
        },

        restart: function(size) {
            UI.notice(UI.delimiter(
                'The minimum delay of ' + UI.value(8, 'seconds') + ' has not been reached'
            ));

            UI.notice(UI.delimiter(
                'Restarting measures with '
                + UI.value(size / 1024 / 1024, 'MB')
                + ' of data...'
            ));
        },

        stop: function() {
            UI.notice(UI.delimiter('Finished measures'));
            UI.$btnStart.prop('disabled', false);
            UI.$btnAbort.prop('disabled', true);
        },

        abort: function() {
            net.upload.abort();
            net.download.abort();
        },

        notice: function(text, newSection) {
            var $o = UI.$output,
                stickToBottom = ($o.scrollTop() + $o.outerHeight()) == $o.prop('scrollHeight');

            $o.append('<br>');
            newSection && $o.append('<br>');

            $o.append('<span class="yellow">[' + module + ']</span> ' + text);

            if (stickToBottom) {
                $o.scrollTop($o.prop('scrollHeight'));
            }
        },

        value: function(value, unit) {
            if (value != null) {
                return '<span class="blue">' + value.toFixed(3) + ' ' + unit + '</span>';
            } else {
                return '<span class="blue">null</span>';
            }
        },

        delimiter: function(text) {
            return '<span class="green">' + text + '</span>';
        }
    };

    /*
     * Network.js configuration
     */

    var net = new Network();

    function start(size) {
        UI.notice(UI.delimiter(
            'Starting ' + rawModule + ' measures'
            + (rawModule != 'latency' ? (' with ' + UI.value(size / 1024 / 1024, 'MB') + ' of data') : '')
            + '...'
        ), true);
    }

    function progress(avg, instant) {
        var output = 'Instant speed: ' + UI.value(instant / 1024 / 1024, 'MBps');
            output += ' // Average speed: ' + UI.value(avg / 1024 / 1024, 'MBps');

        UI.notice(output);
    }

    function end(avg) {
        UI.notice('Final average speed: ' + UI.value(avg / 1024 / 1024, 'MBps'));
        UI.stop();
    }

    net.upload.on('start', start).on('progress', progress).on('restart', UI.restart).on('end', end);
    net.download.on('start', start).on('progress', progress).on('restart', UI.restart).on('end', end);

    net.latency
        .on('start', start)
        .on('end', function(avg, all) {
            all = all.map(function(latency) {
                return UI.value(latency, 'ms');
            });

            all = '[ ' + all.join(' , ') + ' ]';

            UI.notice('Instant latencies: ' + all);
            UI.notice('Average latency: ' + UI.value(avg, 'ms'));
            UI.stop();
        });

    /*
     * Bindings
     */

    UI.$btnStart.on('click', UI.start);
    UI.$btnAbort.on('click', UI.abort);
});
</script>
