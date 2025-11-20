#!/bin/sh

# Custom entrypoint to avoid cp conflict
set -e

# Ensure SWAGGER_JSON is set correctly
if [ -z "$SWAGGER_JSON" ]; then
  echo "SWAGGER_JSON is not set. Exiting."
  exit 1
fi

# Start nginx directly without running the default entrypoint scripts
exec nginx -g "daemon off;"