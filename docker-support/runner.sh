#!/bin/bash

#
# Created by the project template ansible role, this script has the objective of having
# a runners.sh script completely aligned to the one used in python projects.
# This allows for a smoother integrtion of the gitlab-ci pipelines as it avoids the
# necessity of creating project dedicated stages with custom commands.
#

SCRIPT_DIR=$( cd "$( dirname "${BASH_SOURCE[0]}" )" && pwd )

${SCRIPT_DIR}/../create_docker_image $1 $2
