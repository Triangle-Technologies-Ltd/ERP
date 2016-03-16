<?php
global $current_user;
$feeds_tab = erp_crm_get_customer_feeds_nav();
?>
<div class="erp-customer-feeds" id="erp-customer-feeds">
    <input type="hidden" v-model="customer_id" value="<?php echo $customer->id; ?>" name="customer_id">
    <div class="activity-form">
        <ul class="erp-list list-inline nav-item">
            <?php foreach ( $feeds_tab as $name => $value ) : ?>
                <li :class="'<?php echo $name; ?>' == tabShow ? 'active': ''">
                    <a href="#<?php echo $name; ?>" @click.prevent="showTab('<?php echo $name; ?>')">
                        <?php echo sprintf('%s %s', $value['icon'], $value['title'] ); ?>
                    </a>
                </li>
            <?php endforeach ?>
        </ul>

        <div class="nav-content" id="erp-crm-feed-nav-content">
            <form action="" method="post" @submit.prevent = "addCustomerFeed()" id="erp-crm-activity-feed-form">

                <new-note v-if="tabShow == 'new_note'" :feed="" keep-alive></new-note>

                <email-note v-if="tabShow == 'email'"></email-note>

                <log-activity v-if="tabShow == 'log_activity'"></log-activity>

                <schedule-note v-if="tabShow == 'schedule'"></schedule-note>

            </form>
        </div>
    </div>

    <div class="activity-content">

        <ul class="timeline" v-if = "feeds.length">

            <template v-for="( month, feed_obj ) in feeds | formatFeeds">

                <li class="time-label">
                    <span class="bg-red">{{ month | formatDate 'F, Y' }}</span>
                </li>

                <li v-for="feed in feed_obj">

                    <i v-if="feed.type == 'email'" class="fa fa-envelope-o" @click.prevent="toggleFooter"></i>
                    <i v-if="feed.type == 'new_note'" class="fa fa-file-text-o" @click.prevent="toggleFooter"></i>
                    <i v-if="feed.type == 'log_activity'" class="fa fa-list" @click.prevent="toggleFooter"></i>
                    <i v-if="( feed.type == 'log_activity' && isSchedule( feed.start_date )  )" class="fa fa-calendar-check-o" @click.prevent="toggleFooter"></i>

                    <timeline-item :feed="feed" disbale-footer="false"></timeline-item>

                </li>

            </template>

        </ul>

        <div class="feed-load-more" v-show="( feeds.length >= limit ) && !loadingFinish">
            <button @click="loadMoreContent( feeds )" class="button">
                <i class="fa fa-cog fa-spin" v-if="loading"></i>
                &nbsp;<span v-if="!loading"><?php _e( 'Load More', 'wp-erp' ); ?></span>
                &nbsp;<span v-else><?php _e( 'Loading..', 'wp-erp' ); ?></span>
            </button>
        </div>

        <div class="no-activity-found" v-if="!feeds.length">
            <?php _e( 'No Activity found for this Contact', 'wp-erp' ); ?>
        </div>
    </div>
</div>
