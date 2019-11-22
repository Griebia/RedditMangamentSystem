#!/usr/bin/env python

import praw
import sys
import json

def score(elem):
    return elem.score

client_id = sys.argv[1]
client_secret = sys.argv[2]
redirect_uri = sys.argv[3]
sub = sys.argv[4]

subs = sub.split(',')

#  reddit = praw.Reddit(client_id = '_0M8fLv5rf7bcw',
#                      client_secret = 'TLhiSCy3YGXYgz8u4qZQxyaVeCc',
#                      redirect_uri = 'http://stpp.test/api/getcode',
#                      user_agent='my user agent')

reddit = praw.Reddit(client_id = client_id,
                     client_secret = client_secret,
                     redirect_uri = redirect_uri,
                     user_agent='my user agent')

subreddits = []
neededSubmissions = []
for sub in subs:
    subreddit = reddit.subreddit(sub)
    hot = subreddit.hot(limit=20)
    subreddits.append(hot)


for sub in subreddits:
    for submission in sub:
        if not submission.stickied:
            neededSubmissions.append(submission)


neededSubmissions.sort(key=score ,reverse=True)


neededSubRedditJson = []
for submission in neededSubmissions:
    subJson = {}
    subJson['title'] = submission.title
    subJson['score'] = submission.score
    subJson['url'] = submission.url
    neededSubRedditJson.append(subJson)




print(json.dumps(neededSubRedditJson))