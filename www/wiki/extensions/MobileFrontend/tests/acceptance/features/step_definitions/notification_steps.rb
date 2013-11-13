When /^I click on the notification icon$/ do
  on(HomePage).notification_button_element.when_present.click
end

Then /^I go to the notifications page$/ do
  @browser.url.should match Regexp.escape('Special:Notifications')
end

Then /^the notifications overlay appears$/ do
  on(HomePage).notifications_archive_link_element.when_present.should exist
end
