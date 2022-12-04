<?php

use Util\ContentLoader;

require_once("Util/ContentLoader.php");

$contentLoader = new ContentLoader();
$days = $contentLoader->getAvailableDays();
$activeDay = $contentLoader->getActiveDay();
?>
<!doctype html>
<html lang="en">
<head>
   <meta charset="UTF-8">
   <meta name="viewport" content="width=device-width, user-scalable=no, initial-scale=1.0, maximum-scale=1.0, minimum-scale=1.0">
   <meta http-equiv="X-UA-Compatible" content="ie=edge">
   <title>Advent of Code 2022</title>
   <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bulma@0.9.4/css/bulma.min.css">
</head>
<body>

<div class="container p-4">
   <p>
       <?= date('Y-m-d H:i:m (e)') ?>
   </p>
   <div class="box">
      <h1 class="title">Advent of Code 2022</h1>
      <p><em>Advent of Code</em> is an <a href="https://en.wikipedia.org/wiki/Advent_calendar">Advent calendar</a> of small programming puzzles for a variety of skill sets and skill levels that can be
         solved in <a href="https://github.com/search?q=advent+of+code">any</a> programming language you like.</p>
   </div>
   <div class="columns">
      <div class="column is-three-quarters">
         <div class="box px-0">
            <aside class="tabs is-boxed is-small">
               <ul>
                   <?php for ($i = 1; $i <= $days; $i++) { ?>
                      <li
                          <?php if ($i == $activeDay) { ?>class="is-active" <?php } ?>
                          id="tab-<?= $i ?>"
                          data-day="<?= $i ?>"
                      >
                         <a onclick="openDay(<?= $i ?>)"><?= $i ?></a>
                      </li>
                   <?php } ?>
               </ul>
            </aside>

            <div class="day-description px-4">
                <?php for ($i = 1; $i <= $days; $i++) { ?>
                   <section class="content <?php if ($i != $activeDay) { ?>is-hidden<?php } ?>" id="day-<?= $i ?>">
                       <?= $contentLoader->loadTask($i); ?>
                   </section>
                <?php } ?>
            </div>
         </div>
      </div>
      <div class="column is-one-quarter">
         <div style="position: sticky; top: 1rem">
            <div class="box p-0">
               <div class="py-4 is-flex is-justify-content-center">
                  <button class="button is-small is-success" onclick="runSolution()">Run Solution</button>
               </div>
               <table class="table is-fullwidth">
                  <thead>
                  <tr>
                     <th>Part</th>
                     <th style="width: 100%">Result</th>
                  </tr>
                  </thead>
                  <tbody>
                  <tr>
                     <td class="has-text-weight-bold">1.</td>
                     <td id="part-1">Did not</td>
                  </tr>
                  <tr>
                     <td class="has-text-weight-bold">2.</td>
                     <td id="part-2">run</td>
                  </tr>
                  </tbody>
               </table>
            </div>
            <div class="box">
               <form id="answerFrom">
                  <label for="answer" class="label">Answer</label>
                  <div class="field has-addons">
                     <div class="control">
                        <input class="input" type="number" placeholder="Answer" id="answer" autocomplete="off">
                     </div>
                     <div class="control">
                        <button type="submit" class="button is-info">
                           Submit
                        </button>
                     </div>
                  </div>
               </form>
            </div>
            <div class="notification is-danger is-hidden" id="error-box">
               <button class="delete" onclick="hideError()"></button>
               <p id="error-msg">Error</p>
            </div>
         </div>
      </div>
   </div>
</div>

<script>
   const results = new Array(<?= $days ?>).fill(['Did not', 'run'])

   function openDay(day) {
      const activeSection = document.querySelector('.day-description section:not(.is-hidden)');
      activeSection.classList.toggle('is-hidden');

      const activeTab = document.querySelector('li.is-active');
      activeTab.classList.toggle('is-active');

      const newSection = document.querySelector(`#day-${day}`);
      newSection.classList.toggle('is-hidden');

      const newTab = document.querySelector(`#tab-${day}`);
      newTab.classList.toggle('is-active');

      updateResults(day)
   }

   function updateResults(day) {
      const createButton = part => `<button class="button is-small is-info" onclick="createClass(${day}, ${part})">Create Class</button>`;
      const isNotImplemented = res => res === "Class dont exists";

      const firstResult = results[day - 1][0];
      const secondResult = results[day - 1][1];

      document.querySelector('#part-1').innerHTML = isNotImplemented(firstResult) ? createButton(1) : firstResult
      document.querySelector('#part-2').innerHTML = isNotImplemented(secondResult) ? createButton(2) : secondResult
   }

   function getActiveDay() {
      return document.querySelector('.tabs li.is-active').dataset.day;
   }

   function runSolution() {
      hideError();
      const activeDay = getActiveDay();

      results[activeDay - 1] = ['Loading...', 'Loading...']
      updateResults(activeDay)

      fetch(`run.php?day=${activeDay}`)
         .then(async (r) => {
            try {
               return await r.json()
            } catch (e) {
               showError(r);
               return ["Error", "Occurred"];
            }
         })
         .then(res => {
            if (!Array.isArray(res)) {
               showError(res);
               res = ["Error", "Occurred"];
            }

            results[activeDay - 1] = res
            updateResults(activeDay);
         })
         .catch(err => {
            showError(err)
         })
   }

   function hideError() {
      const box = document.querySelector('#error-box');
      box.classList.add('is-hidden');
   }

   function showError(err) {
      const errMsg = document.querySelector('#error-msg');
      errMsg.innerHTML = err;

      const box = document.querySelector('#error-box');
      box.classList.remove('is-hidden');
   }

   function createClass(day, part) {
      fetch(`create.php?day=${day}&part=${part}`)
         .then(async r => {
            const res = await r.text();


            results[day - 1][part - 1] = res === '1'
               ? 'Not Implemented'
               : 'Creation failed';

            updateResults(day);
         });
   }

   const answerFrom = document.querySelector('#answerFrom');
   answerFrom.addEventListener('submit', sendAnswer);

   function sendAnswer(e) {
      e.preventDefault();
      const answer = document.querySelector('#answer').value;
      const activeDay = getActiveDay();
      const url = `https://adventofcode.com/2022/day/${activeDay}/answer`
      const data = new FormData();
      data.append('level', '1');
      data.append('answer', answer);

      if (confirm(`Do you really want to submit answer: ${answer}`)) {
         fetch(url, {
            method: 'POST',
            body: new URLSearchParams(data)
         })
      }
   }
</script>
</body>
</html>
