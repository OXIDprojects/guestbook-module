[{capture append="oxidBlock_content"}]
    <h1 class="pageHead">[{$oView->getTitle()}]</h1>
    <div class="listRefine clear bottomRound">
        [{include file="widget/locator/listlocator.tpl" locator=$oView->getPageNavigation() sort=true}]
    </div>
    <div class="reviews">
        [{include file="form/oeguestbookguestbook.tpl"}]
        <dl>
            [{if $oView->getEntries()}]
            [{foreach from=$oView->getEntries() item=entry}]
            <dt class="clear item">
                        <span>[{$entry->oxuser__oxfname->value}] [{oxmultilang ident="OEGUESTBOOK_WRITES" suffix="COLON"}] <span>[{$entry->oeguestbookentry__oxcreate->value|date_format:"%d.%m.%Y"}] [{$entry->oeguestbookentry__oxcreate->value|date_format:"%H:%M"}]<span></span></span>
            </dt>
            <dd>
                <div class="description">[{$entry->oeguestbookentry__oxcontent->value|nl2br}]</div>
            </dd>
            [{/foreach}]
            [{else}]
            <dt>
                [{oxmultilang ident="OEGUESTBOOK_NO_ENTRY_AVAILABLE"}]
            </dt>
            <dd></dd>
            [{/if}]
        </dl>
        [{include file="widget/locator/listlocator.tpl" locator=$oView->getPageNavigation() place="bottom"}]
    </div>
    [{/capture}]
[{include file="layout/page.tpl" sidebar="Left"}]
