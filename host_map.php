<?php
/**
 * Maps req_host to Jenkins project name
 */

$host_map = array(
  // testing
  "localhost:3000" => "v2-cuttlefish.clearlink",

  // internal (dev)
  "cuttlefish.dev.aws.clearlink.com" => "v2-cuttlefish.clearlink",
  "cuttlefish.staging.aws.clearlink.com" => "v2-cuttlefish.clearlink",
  "cuttlefish.clearlink.com" => "v2-cuttlefish.clearlink",

  // telco1
  "attsavings.dev.aws.clearlink.com" => "v2-attsavings",
  "attsavings.staging.aws.clearlink.com" => "v2-attsavings",
  "attsavings.com" => "v2-attsavings",

  "attexperts.dev.aws.clearlink.com" => "v2-attexperts",
  "attexperts.staging.aws.clearlink.com" => "v2-attexperts",
  "attexperts.com" => "v2-attexperts",

  // frontier
  "west.frontier.dev.aws.clearlink.com" => "v2-west.frontier",
  "west.frontier.staging.aws.clearlink.com" => "v2-west.frontier",
  "west.frontier.com" => "v2-west.frontier",

  // biz
  "business.frontier.dev.aws.clearlink.com" => "v2-business.frontier",
  "business.frontier.staging.aws.clearlink.com" => "v2-business.frontier",
  "business.frontier.com" => "v2-business.frontier",

  // energy
  "amigoenergy.dev.aws.clearlink.com" => "v2-amigoenergy",
  "amigoenergy.staging.aws.clearlink.com" => "v2-amigoenergy",
  "amigoenergy.com" => "v2-amigoenergy",

  "amigoenergyplans.dev.aws.clearlink.com" => "v2-amigoenergyplans",
  "amigoenergyplans.staging.aws.clearlink.com" => "v2-amigoenergyplans",
  "amigoenergyplans.com" => "v2-amigoenergyplans",

  "justenergy.dev.aws.clearlink.com" => "v2-justenergy",
  "justenergy.staging.aws.clearlink.com" => "v2-justenergy",
  "justenergy.com" => "v2-justenergy",

  "taraenergy.dev.aws.clearlink.com" => "v2-taraenergy",
  "taraenergy.staging.aws.clearlink.com" => "v2-taraenergy",
  "taraenergy.com" => "v2-taraenergy",

  // sat/sec
  "centurylinkquote.dev.aws.clearlink.com" => "v2-centurylinkquote",
  "centurylinkquote.staging.aws.clearlink.com" => "v2-centurylinkquote",
  "centurylinkquote.clearlink.com" => "v2-centurylinkquote",

  "getcenturylink.dev.aws.clearlink.com" => "v2-getcenturylink",
  "getcenturylink.staging.aws.clearlink.com" => "v2-getcenturylink",
  "getcenturylink.clearlink.com" => "v2-getcenturylink",

  "vivintsource.dev.aws.clearlink.com" => "v2-vivintsource",
  "vivintsource.staging.aws.clearlink.com" => "v2-vivintsource",
  "vivintsource.com" => "v2-vivintsource",

  "satelliteinternet.dev.aws.clearlink.com" => "v2-satelliteinternet",
  "satelliteinternet.staging.aws.clearlink.com" => "v2-satelliteinternet",
  "satelliteinternet.com" => "v2-satelliteinternet",

  "usdirect.dev.aws.clearlink.com" => "v2-usdirect",
  "usdirect.staging.aws.clearlink.com" => "v2-usdirect",
  "usdirect.com" => "v2-usdirect"


);
