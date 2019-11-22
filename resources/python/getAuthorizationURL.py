#!/usr/bin/env python

import praw
import sys

client_id = sys.argv[1]
client_secret = sys.argv[2]
redirect_uri = sys.argv[3]
state = sys.argv[4]


reddit = praw.Reddit(client_id = client_id,
                     client_secret = client_secret,
                     redirect_uri = redirect_uri,
                     user_agent='my user agent')
# print(reddit.read_only)
print(reddit.auth.url(['identity,edit,flair,history,modconfig,modflair,modlog,modposts,modwiki,mysubreddits,privatemessages,read,report,save,submit,subscribe,vote,wikiedit, wikiread'], state, 'permanent'))
