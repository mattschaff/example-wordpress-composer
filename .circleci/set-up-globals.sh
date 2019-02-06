#!/bin/bash

set -ex

#=====================================================================================================================
# Start EXPORTing needed environment variables
# Circle CI 2.0 does not yet expand environment variables so they have to be manually EXPORTed
# Once environment variables can be expanded this section can be removed
# See: https://discuss.circleci.com/t/unclear-how-to-work-with-user-variables-circleci-provided-env-variables/12810/11
# See: https://discuss.circleci.com/t/environment-variable-expansion-in-working-directory/11322
# See: https://discuss.circleci.com/t/circle-2-0-global-environment-variables/8681
#=====================================================================================================================

# GitLab
if [ -n "$GITLAB_CI" ]
then
	# Set CI variables
	$BASH_ENV=$HOME/.bashrc
	echo 'export BRANCH=$(echo $CI_MERGE_REQUEST_SOURCE_BRANCH_NAME | grep -v '"'"'^\(master\|[0-9]\+.x\)$'"'"')' >> $BASH_ENV
	echo 'export CI_ENV=ci-$CI_JOB_ID' >> $BASH_ENV
	echo 'export CURRENT_BRANCH=$CI_MERGE_REQUEST_SOURCE_BRANCH_NAME' >> $BASH_ENV
	echo 'export CI_PR_URL=$CI_MERGE_REQUEST_ID' >> $BASH_ENV

	# Configure git credentials
	git config --global user.email "$GITLAB_USER_EMAIL"
  	git config --global user.name "Gitlab CI"
fi

# CircleCI
if [ -n "$CIRCLECI" ]
then
	# Set CI variables
	echo 'export BRANCH=$(echo $CIRCLE_BRANCH | grep -v '"'"'^\(master\|[0-9]\+.x\)$'"'"')' >> $BASH_ENV
	echo 'export CI_ENV=ci-$CIRCLE_BUILD_NUM' >> $BASH_ENV
	echo 'export CURRENT_BRANCH=$CIRCLE_BRANCH' >> $BASH_ENV
	echo 'export CI_PR_URL=$CIRCLE_PULL_REQUEST' >> $BASH_ENV

	# Configure git credentials
	git config --global user.email "$GIT_EMAIL"
	git config --global user.name "CircleCI"
fi

source $BASH_ENV

echo 'export PATH=$PATH:$HOME/bin:$HOME/terminus/bin' >> $BASH_ENV
echo 'export PR_ENV=${BRANCH:+pr-$BRANCH}' >> $BASH_ENV

source $BASH_ENV

# If we are on a pull request
if [[ $CURRENT_BRANCH != "master" && -n ${CI_PR_URL+x} ]]
then
	# Then use a pr- branch/multidev
	PR_NUMBER=${CI_PR_URL##*/}
	PR_BRANCH="pr-${PR_NUMBER}"
	echo "export DEFAULT_ENV=pr-${PR_NUMBER}" >> $BASH_ENV
else
	# otherwise make the branch name multidev friendly
	if [[ $CURRENT_BRANCH == "master" ]]
	then
		echo "export DEFAULT_ENV=dev" >> $BASH_ENV
	else
		echo 'export DEFAULT_ENV=$(echo ${PR_ENV:-$CI_ENV} | tr '"'"'[:upper:]'"'"' '"'"'[:lower:]'"'"' | sed '"'"'s/[^0-9a-z-]//g'"'"' | cut -c -11 | sed '"'"'s/-$//'"'"')' >> $BASH_ENV
	fi
fi

echo 'export TERMINUS_ENV=${TERMINUS_ENV:-$DEFAULT_ENV}' >> $BASH_ENV
source $BASH_ENV

#===========================================
# End EXPORTing needed environment variables
#===========================================

# Add a Git token for Composer
if [ -n "$GITHUB_TOKEN" ] ; then
  composer config --global github-oauth.github.com $GITHUB_TOKEN
fi

# Bail on errors
set +ex

# Make sure Terminus is installed
terminus --version

# Authenticate with Terminus
terminus auth:login -n --machine-token="$TERMINUS_TOKEN"

# Disable host checking
mkdir -p $HOME/.ssh && echo "StrictHostKeyChecking no" >> "$HOME/.ssh/config"

# Ignore file permissions.
git config --global core.fileMode false